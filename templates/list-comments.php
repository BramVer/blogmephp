<?php
/**
 * @var PDO $pdo
 * @var integer $postID
 */
?>

<form method='POST'
      action='view-post.php?action=delete-comment&amp;post_id=<?php echo $postID ?>&amp;'
      class='comment-list'>
  <h3><?php echo countCommentsForPost($pdo, $postID) ?> comments</h3>

  <?php foreach(getCommentsForPost($pdo, $postID) as $comment) ?>
    <div class='comment'>
      <div class='comment-meta'>
        Comment from
        <?php echo htmlEscape($comment['name']) ?>
        on
        <?php echo convertSqlDate($comment['created_at']) ?>

        <!-- Allow deletion for authorised users -->
        <?php if(isLoggedIn()): ?>
          <input type='submit' name='delete-comment[<?php echo $comment["id"] ?>]' value='Delete'/>
        <?php endif ?>

      </div>
      <div class='comment-body'>
        <!-- Next line is already escaped, no need to do so again -->
        <?php echo convertNewLinesToParagraphs($comment['test']) ?>
      </div>
      <div class='comment-body'>
        <?php echo convertNewLinesToParagraphs($comment['text']) ?>
      </div>
    </div>
  <?php endforeach ?>
</div>
