<?php

namespace XenBulletins\BrandHub\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class OwnerPage extends AbstractHandler
{
	public function getContainerWith()
	{
		$visitor = \XF::visitor();

		return ['Attachment'];
	}

	public function canView(Attachment $attachment, Entity $container, &$error = null)
	{
		
		return $container->canView();
	}

	public function canManageAttachments(array $context, &$error = null)
	{

		return true;
	}

	public function validateAttachmentUpload(\XF\Http\Upload $upload, \XF\Attachment\Manipulator $manipulator)
	{
          
		if (!$upload->getTempFile())
		{
			return;
		}

		$extension = $upload->getExtension();

		$repo = \XF::repository('XenBulletins\BrandHub:OwnerPage');


                
		$mediaType = $repo->getMediaTypeFromExtension($extension);

		if (in_array($mediaType, ['audio', 'image', 'video']))
		{
			$visitor = \XF::visitor();
			$constraints = $manipulator->getConstraints();

			$thisFileSize = $runningTotal = $upload->getFileSize();
			$newAttachments = $manipulator->getNewAttachments();
			if (count($newAttachments))
			{
				foreach ($newAttachments AS $attachment)
				{
					/** @var \XF\Entity\Attachment $attachment */
					$runningTotal += intval($attachment->getFileSize());
				}
			}
		}
	}

	
	

	public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
	{
		if (!$container)
		{
			return;
		}

	}

	public function getConstraints(array $context)
	{

		$em = \XF::em();

		if (!empty($context['page_id']))
		{
			$Item = $em->find('XenBulletins\BrandHub:OwnerPage', intval($context['page_id']));
			return $Item->getAttachmentConstraints();
		}
		else
		{
			$Item = $em->create('XenBulletins\BrandHub:OwnerPage');
			return $Item->getAttachmentConstraints();
		}
	}

	public function getContainerIdFromContext(array $context)
	{
		return isset($context['page_id']) ? intval($context['page_id']) : null;
	}

	public function getContainerLink(Entity $container, array $extraParams = [])
	{
		return \XF::app()->router('public')->buildLink('item-list', $container, $extraParams);
	}

	public function getContext(Entity $entity = null, array $extraContext = [])
	{
		if ($entity instanceof \XenBulletins\BrandHub\Entity\OwnerPage)
		{
			$extraContext['page_id'] = $entity->page_id;
		}
		else
		{
			throw new \InvalidArgumentException("Entity must be Owner Page");
		}

		return $extraContext;
	}
}