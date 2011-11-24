<?php

class Message_dal extends Model
{
	function Message_dal()
	{
		parent::__construct();
	}

	function unread_messages($user_id)
	{
		return (int)$this->db->query("SELECT count(inbox_id) AS msg_count FROM pm_inbox WHERE pm_inbox.read = 0 AND to_id = ?", $user_id)->row()->msg_count;
	}

	function set_read($user_id, $message_id)
	{
		$this->db->query("UPDATE pm_inbox SET pm_inbox.read = 1 WHERE message_id = ? AND to_id = ?", array((int)$message_id, $user_id));
	}

	function set_unread_in_array($user_id, $messages) {
		$this->db->query("UPDATE pm_inbox SET pm_inbox.read = 0 WHERE message_id IN (". implode($messages, ',') .") AND to_id = ?", $user_id);
	}

	function set_read_in_array($user_id, $messages) {
		$this->db->query("UPDATE pm_inbox SET pm_inbox.read = 1 WHERE message_id IN (". implode($messages, ',') .") AND to_id = ?", $user_id);
	}

	function delete_in_array_inbox($user_id, $messages)
	{
		$this->db->query("UPDATE pm_inbox SET pm_inbox.deleted = 1, pm_inbox.read = 1 WHERE message_id IN (". implode($messages, ',') .") AND to_id = ?", $user_id);
	}

	function delete_in_array_outbox($user_id, $messages)
	{
		$this->db->query("UPDATE pm_outbox SET pm_outbox.deleted = 1 WHERE message_id IN (". implode($messages, ',') .") AND from_id = ?", $user_id);
	}

	function get_message($user_id, $message_id)
	{
		$sql = "
			SELECT DISTINCT
				users.username,
				users.id AS sender_id,
				pm_inbox.read,
				pm_inbox.read_receipt,
				pm_inbox.to_id,
				pm_content.subject,
				pm_content.content,
				pm_content.created,
				pm_content.message_id
			FROM pm_inbox
			LEFT JOIN pm_content
				ON pm_inbox.message_id = pm_content.message_id
			LEFT JOIN pm_outbox
				ON pm_outbox.message_id = pm_content.message_id
			LEFT JOIN users
				ON pm_inbox.from_id = users.id
			WHERE pm_content.message_id = ?
				AND (pm_inbox.to_id = ? OR pm_inbox.from_id = ?)
				AND (pm_inbox.deleted = 0 OR pm_outbox.deleted = 0)
			GROUP BY pm_content.message_id";

		$result = $this->db->query($sql, array(
			$message_id,
			$user_id,
			$user_id
		));

		return $result->num_rows === 1 ? $result : FALSE;
	}

	function get_inbox($user_id)
	{
		$sql = "
			SELECT
				users.username,
				pm_inbox.read,
				pm_content.message_id,
				pm_content.subject,
				pm_content.created,
                                acquaintances.type AS buddy_type
			FROM pm_inbox
			RIGHT JOIN pm_content
				ON pm_inbox.message_id = pm_content.message_id
			LEFT JOIN users
				ON pm_inbox.from_id = users.id
			LEFT JOIN acquaintances
				ON acquaintances.acq_user_id = users.id
                                AND acquaintances.user_id = ?
			WHERE pm_inbox.to_id = ?
			AND pm_inbox.deleted = 0
			ORDER BY pm_content.created DESC";

		return $this->db->query($sql, array($user_id, $user_id));
	}

	function get_outbox($user_id)
	{
		$sql = "
			SELECT
				GROUP_CONCAT(users.username) AS usernames,
				pm_content.message_id,
				pm_content.subject,
				pm_content.created
			FROM pm_outbox
			RIGHT JOIN pm_content
				ON pm_outbox.message_id = pm_content.message_id
			LEFT JOIN users
				ON users.id = pm_outbox.to_id
			WHERE pm_outbox.from_id = ?
			AND pm_outbox.deleted = 0
			GROUP BY pm_content.message_id
			ORDER BY pm_content.created DESC";

		return $this->db->query($sql, $user_id);
	}

	function new_message($data)
	{
		$sql = "
			INSERT INTO pm_content
				(subject, content, created)
			VALUES
			(?, ?, ?)";

		$this->db->query($sql, array(
			$data['subject'],
			$data['content'],
			date("Y-m-d H:i:s", utc_time())
		));

		return $this->db->insert_id();
	}

	function new_inbox($recipient, $message, $read_receipt)
	{
		$sql = "
			INSERT INTO pm_inbox
				(to_id, from_id, message_id, read_receipt)
			VALUES
			(?, ?, ?, ?)";

		$this->db->query($sql, array(
			$recipient,
			$message['sender'],
			$message['id'],
			$read_receipt == 'receipt' ? '1' : '0'
		));
	}

	function new_outbox($recipient, $message)
	{
		$sql = "
			INSERT INTO pm_outbox
				(to_id, from_id, message_id)
			VALUES
			(?, ?, ?)";

		$this->db->query($sql, array(
			$recipient,
			$message['sender'],
			$message['id']
		));
	}
}