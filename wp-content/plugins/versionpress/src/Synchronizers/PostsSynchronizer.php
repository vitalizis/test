<?php
namespace VersionPress\Synchronizers;

use VersionPress\Database\Database;
use VersionPress\Utils\WordPressCacheUtils;
use wpdb;

/**
 * Posts synchronizer. Fixes comment counts for restored posts.
 */
class PostsSynchronizer extends SynchronizerBase
{

    protected function doEntitySpecificActions()
    {
        parent::doEntitySpecificActions();
        WordPressCacheUtils::clearPostCache(array_column($this->entities, 'vp_id'), $this->database);
        return true;
    }

    /**
     * @param Database $database
     */
    public static function fixCommentCounts($database)
    {
        $sql = "update {$database->prefix}posts set comment_count =
     (select count(*) from {$database->prefix}comments
      where comment_post_ID = {$database->prefix}posts.ID and comment_approved = 1
     );";
        $database->query($sql);
    }
}
