<?php

namespace FS\DownloadThreadAttachments\XF\Entity;

class Thread extends XFCP_Thread
{
    public function getAttachmentPostIds()
    {
            return $this->db()->fetchAllColumn("
                    SELECT post_id
                    FROM xf_post
                    WHERE thread_id = ?
                    AND attach_count > 0
                    ORDER BY post_date
            ", $this->thread_id);
    }
    
}
