      </div>
    </div>
    <a name="bottom"></a>
    <div id="bottom"></div>

    <script src="/js/jquery-1.6.4.min.js"></script>
    <script>
      $('#login-form').bind('submit', function(e) {
        e.preventDefault();

        var data = {
          username: $('#username').val(),
          password: $('#password').val()
        };

        $.ajax({
          url: '/beta/login', type: 'POST', data: data
        }).fail(function(data) {
          $('.error').text(JSON.parse(data.responseText).error);
        }).then(function() {
          document.location.href = '';
        });
      });
    </script>

  </body>
</html>