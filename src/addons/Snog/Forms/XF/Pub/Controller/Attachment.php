<?php

namespace Snog\Forms\XF\Pub\Controller;

class Attachment extends XFCP_Attachment
{
	public function actionUploadAutomated()
	{
		$type = $this->filter('type', 'str');
		$handler = $this->getAttachmentRepo()->getAttachmentHandler($type);
		if (!$handler)
		{
			return $this->noPermission();
		}

		$context = $this->filter('context', 'array-str');
		$hash = $this->filter('hash', 'str');
		if (!$hash)
		{
			return $this->noPermission();
		}

		$manipulator = new \XF\Attachment\Manipulator($handler, $this->getAttachmentRepo(), $context, $hash);

		if ($this->isPost())
		{
			$json = [];
			$delete = $this->filter('delete', 'uint');

			if ($delete)
			{
				$manipulator->deleteAttachment($delete);
				$json['delete'] = $delete;
			}

			$uploadError = null;
			if ($manipulator->canUpload($uploadError))
			{
				$upload = $this->request->getFile('upload', false, false);
				if ($upload)
				{
					$attachment = $manipulator->insertAttachmentFromUpload($upload, $error);
					if (!$attachment) return $this->error($error);

					$legacyXF = \XF::$versionId < 2020000;

					$json['attachment'] = [
						'attachment_id' => $attachment->attachment_id,
						'filename' => $attachment->filename,
						'file_size' => $attachment->file_size,
						'thumbnail_url' => $attachment->thumbnail_url,
						'is_video' => $attachment->is_video,
						//'video_url' => $attachment->video_url,
					];

					if (!$legacyXF)
					{
						$json['attachment']['link'] = $attachment->direct_url;
					}
					else
					{
						$json['attachment']['link'] = $attachment->is_video
							? $attachment->video_url
							: $this->buildLink('attachments', $attachment, ['hash' => $attachment->temp_hash]);
					}

					$json['link'] = $json['attachment']['link'];
					$json = $handler->prepareAttachmentJson($attachment, $context, $json);
				}
			}
			else if ($uploadError)
			{
				return $this->error($uploadError);
			}

			$reply = $this->redirect($this->buildLink('attachments/upload', null, [
				'type' => $type,
				'context' => $context,
				'hash' => $hash
			]));

			$reply->setJsonParams($json);

			return $reply;
		}
		else
		{
			$uploadError = null;
			$canUpload = $manipulator->canUpload($uploadError);

			$viewParams = [
				'handler' => $handler,
				'constraints' => $manipulator->getConstraints(),
				'canUpload' => $canUpload,
				'uploadError' => $uploadError,
				'existing' => $manipulator->getExistingAttachments(),
				'new' => $manipulator->getNewAttachments(),
				'hash' => $hash,
				'type' => $type,
				'context' => $context
			];

			return $this->view('XF:Attachment\Upload', 'attachment_upload', $viewParams);
		}
	}
}