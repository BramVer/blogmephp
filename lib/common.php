<?php
/**
 * Gets the root path of the project.
 *
 * @return string
 */
 function getRootPath()
 {
   return realpath(__DIR__ . '/..');
 }

/**
 * Gets the full path for the database file.
 *
 * @return string
 */
function getDatabasePath()
{
  return getRootPath() . '/data/data.sqlite';
}

/**
 * Gets the DSN for the SQLite connection.
 *
 * @return string
 */
function getDsn()
{
    return 'sqlite:' . getDatabasePath();
}
/**
 * Gets the PDO object for database acccess.
 *
 * @return \PDO
 */
function getPDO()
{
    return new PDO(getDsn());
}

/**
 * Escapes HTML so it is safe to output
 *
 * @param string $html
 * @return string
 */
function htmlEscape($html)
{
    return htmlspecialchars($html, ENT_HTML5, 'UTF-8');
}

function getSQLDateForNow()
{
  return date('Y-m-d H:i:s');
}

function convertSQLDate($sqlDate)
{
  /* @var $date DateTime */
  $date = DateTime::createFromFormat('Y-m-d H:i:s', $sqlDate);

  return $date -> format('d M Y, H:i');
}

/**
 * Converts unsafe text to safe, paragraphed, HTML
 *
 * @param string $text
 * @return string
 */
function convertNewLinesToParagraphs($text)
{
  return '<p>' . str_replace('\n', '</p><p>', htmlEscape($text)) . '</p>';
}

/**
 * Returns the number of comments for the specified post
 *
 * @param integer $postId
 * @return integer
 */
function countCommentsForPost($postID)
{
  $pdo = getPDO();
  $sql = "SELECT COUNT(*) c
          FROM comment
          WHERE post_id = :postid";

  $stmt = $pdo -> prepare($sql);
  $stmt -> execute(
    array('post_id' => $postID, )
  );

  return (int) $stmt -> fetchColumn();
}

/**
 * Returns all the comments for the specified post
 *
 * @param integer $postId
 */
function getCommentsForPost($postID)
{
  $pdo = getPDO();
  $sql = "SELECT id, name, text, created_at, website
          FROM comment
          WHERE post_id = :post_id";

  $stmt = $pdo -> prepare($sql);
  $stmt -> execute(
    array('post_id' => $postID, )
  );

  return $stmt -> fetchAll(PDO::FETCH_ASSOC);
}

function redirectAndExit($script)
{
  // Get domain-relative url and work out dir
  $relativeURL = $_SERVER['PHP_SELF'];
  $urlFolder = substr($relativeURL, 0, strrpos($relativeURL, '/') + 1);

  // Redirect to full URL
  $host = $_SERVER['HTTP_HOST'];
  $fullURL = 'http://' . $host . $urlFolder . $script;

  header('Location: ' . $fullURL);
  exit();
}

?>
