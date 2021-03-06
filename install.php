<?php
  require_once 'lib/common.php';
  require_once 'lib/install.php';

  // Store stuff in the session to survive redirects
  session_start();

  // Only run installer when it comes from form
  if($_POST)
  {
    // Install
    $pdo = getPDO();
    list($rowCounts, $error) = installBlog($pdo);

    $password = '';
    if(!$error)
    {
      $username = 'admin';
      list($password, $error) = createUser($pdo, $username);
    }

    $_SESSION['count'] = $rowCounts;
    $_SESSION['error'] = $error;
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['try-install'] = true;

    // Redirect from POST to GET
    redirectAndExit('install.php');
  }

  // Check if install was triggered
  $attempted = false;
  if(isset($_SESSION['try-install']))
  {
    $attempted = true;
    $count = $_SESSION['count'];
    $error = $_SESSION['error'];
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    // Unsert session vars, so we only report install/failure once
    unset($_SESSION['count']);
    unset($_SESSION['error']);
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['try-install']);
  }
 ?>

 <!DOCTYPE html>
 <html>
     <head>
         <title>Blog installer</title>
         <?php require 'templates/head.php' ?>
     </head>
     <body>
      <?php if ($attempted): ?>
        <?php if ($error): ?>
            <div class="error box">
                <?php echo $error ?>
            </div>
        <?php else: ?>
            <div class="success box">
              The database and demo data was created OK.

              <!-- Report counts per table -->
              <?php foreach (array('post', 'comment') as $tableName): ?>
                  <?php if (isset($count[$tableName])): ?>
                      <!-- Prints count -->
                      <?php echo $count[$tableName] ?> new
                      <!-- Prints thingy name -->
                      <?php echo $tableName ?>s
                      were created.
                  <?php endif ?>
              <?php endforeach ?>

              <!-- Print unhashed password -->
              The new '<?php echo htmlEscape($username) ?>' password is
              <span class='install-password'>
                <?php echo htmlEscape($password) ?>
              </span>
            </div>
            <p>
							<a href="index.php">View the blog</a>,
							or <a href="install.php">install again</a>.
						</p>
        <?php endif ?>

      <?php else: ?>

        <p>Click the install button to reset the database.</p>
        <form method="post">
            <input
                name="install"
                type="submit"
                value="Install"
            />
        </form>

      <?php endif ?>
     </body>
 </html>
