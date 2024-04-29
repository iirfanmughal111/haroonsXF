<?php

// ################## THIS IS A GENERATED FILE ##################
// DO NOT EDIT DIRECTLY. EDIT THE CLASS EXTENSIONS IN THE CONTROL PANEL.

namespace nick97\TraktIntegration\Helper
{
	class XFCP_Tmdb extends \Snog\Movies\Helper\Tmdb {}
}

namespace nick97\TraktIntegration\Helper\Tmdb
{
	class XFCP_Show extends \Snog\TV\Helper\Tmdb\Show {}
}

namespace nick97\TraktIntegration\Pub\Controller
{
	class XFCP_Movies extends \Snog\Movies\Pub\Controller\Movies {}
	class XFCP_TV extends \Snog\TV\Pub\Controller\TV {}
}

namespace nick97\TraktIntegration\Service\Movie
{
	class XFCP_Creator extends \Snog\Movies\Service\Movie\Creator {}
}

namespace nick97\TraktIntegration\Service\Thread\TypeData
{
	class XFCP_MovieCreator extends \Snog\Movies\Service\Thread\TypeData\MovieCreator {}
	class XFCP_TvCreator extends \Snog\TV\Service\Thread\TypeData\TvCreator {}
}

namespace nick97\TraktIntegration\Service\TV
{
	class XFCP_Creator extends \Snog\TV\Service\TV\Creator {}
}

namespace nick97\TraktIntegration\XF\Entity
{
	class XFCP_Thread extends \XF\Entity\Thread {}
}

namespace nick97\TraktIntegration\XF\ForumType
{
	class XFCP_Discussion extends \XF\ForumType\Discussion {}
}

namespace nick97\TraktIntegration\XF\Service\Thread
{
	class XFCP_Creator extends \XF\Service\Thread\Creator {}
}