<?php
// FROM HASH: d6c915750414590fd71cb1e09d1bc25b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.siropuChatUploads
{
	.attachUploadList
	{
		li
		{
			cursor: pointer;

			img
			{
				&:hover
				{
					opacity: 0.5;
				}
			}

			&.js-attachmentFileSelected
			{
				.file-delete
				{
					display: none;
				}
				img
				{
					opacity: 0.3;
				}
			}
		}
	}

	.formSubmitRow-controls
	{
		display: none;
	}
}';
	return $__finalCompiled;
}
);