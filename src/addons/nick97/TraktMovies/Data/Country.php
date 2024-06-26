<?php

namespace nick97\TraktMovies\Data;

class Country
{
	public function getCountryOptions($filterDisabled = false)
	{
		$output = [];
		foreach ($this->getCountryData($filterDisabled) as $code => $info)
		{
			$output[$code] = \XF::phrase($info['phrase']);
		}
		return $output;
	}

	public function getCountryData($filterDisabled = false)
	{
		$countryData = [
			'AF' => ['phrase' => 'trakt_movies_country.af'],
			'AL' => ['phrase' => 'trakt_movies_country.al'],
			'DZ' => ['phrase' => 'trakt_movies_country.dz'],
			'AS' => ['phrase' => 'trakt_movies_country.as'],
			'AD' => ['phrase' => 'trakt_movies_country.ad'],
			'AO' => ['phrase' => 'trakt_movies_country.ao'],
			'AI' => ['phrase' => 'trakt_movies_country.ai'],
			'AQ' => ['phrase' => 'trakt_movies_country.aq'],
			'AG' => ['phrase' => 'trakt_movies_country.ag'],
			'AR' => ['phrase' => 'trakt_movies_country.ar'],
			'AM' => ['phrase' => 'trakt_movies_country.am'],
			'AW' => ['phrase' => 'trakt_movies_country.aw'],
			'AU' => ['phrase' => 'trakt_movies_country.au'],
			'AT' => ['phrase' => 'trakt_movies_country.at'],
			'AZ' => ['phrase' => 'trakt_movies_country.az'],
			'BS' => ['phrase' => 'trakt_movies_country.bs'],
			'BH' => ['phrase' => 'trakt_movies_country.bh'],
			'BD' => ['phrase' => 'trakt_movies_country.bd'],
			'BB' => ['phrase' => 'trakt_movies_country.bb'],
			'BY' => ['phrase' => 'trakt_movies_country.by'],
			'BE' => ['phrase' => 'trakt_movies_country.be'],
			'BZ' => ['phrase' => 'trakt_movies_country.bz'],
			'BJ' => ['phrase' => 'trakt_movies_country.bj'],
			'BM' => ['phrase' => 'trakt_movies_country.bm'],
			'BT' => ['phrase' => 'trakt_movies_country.bt'],
			'BO' => ['phrase' => 'trakt_movies_country.bo'],
			'BA' => ['phrase' => 'trakt_movies_country.ba'],
			'BW' => ['phrase' => 'trakt_movies_country.bw'],
			'BV' => ['phrase' => 'trakt_movies_country.bv'],
			'BR' => ['phrase' => 'trakt_movies_country.br'],
			'IO' => ['phrase' => 'trakt_movies_country.io'],
			'BN' => ['phrase' => 'trakt_movies_country.bn'],
			'BG' => ['phrase' => 'trakt_movies_country.bg'],
			'BF' => ['phrase' => 'trakt_movies_country.bf'],
			'BI' => ['phrase' => 'trakt_movies_country.bi'],
			'KH' => ['phrase' => 'trakt_movies_country.kh'],
			'CM' => ['phrase' => 'trakt_movies_country.cm'],
			'CA' => ['phrase' => 'trakt_movies_country.ca'],
			'CV' => ['phrase' => 'trakt_movies_country.cv'],
			'KY' => ['phrase' => 'trakt_movies_country.ky'],
			'CF' => ['phrase' => 'trakt_movies_country.cf'],
			'TD' => ['phrase' => 'trakt_movies_country.td'],
			'CL' => ['phrase' => 'trakt_movies_country.cl'],
			'CN' => ['phrase' => 'trakt_movies_country.cn'],
			'CX' => ['phrase' => 'trakt_movies_country.cx'],
			'CC' => ['phrase' => 'trakt_movies_country.cc'],
			'CO' => ['phrase' => 'trakt_movies_country.co'],
			'KM' => ['phrase' => 'trakt_movies_country.km'],
			'CG' => ['phrase' => 'trakt_movies_country.cg'],
			'CD' => ['phrase' => 'trakt_movies_country.cd'],
			'CK' => ['phrase' => 'trakt_movies_country.ck'],
			'CR' => ['phrase' => 'trakt_movies_country.cr'],
			'CI' => ['phrase' => 'trakt_movies_country.ci'],
			'HR' => ['phrase' => 'trakt_movies_country.hr'],
			'CU' => ['phrase' => 'trakt_movies_country.cu'],
			'CY' => ['phrase' => 'trakt_movies_country.cy'],
			'CZ' => ['phrase' => 'trakt_movies_country.cz'],
			'DK' => ['phrase' => 'trakt_movies_country.dk'],
			'DJ' => ['phrase' => 'trakt_movies_country.dj'],
			'DM' => ['phrase' => 'trakt_movies_country.dm'],
			'DO' => ['phrase' => 'trakt_movies_country.do'],
			'EC' => ['phrase' => 'trakt_movies_country.ec'],
			'EG' => ['phrase' => 'trakt_movies_country.eg'],
			'SV' => ['phrase' => 'trakt_movies_country.sv'],
			'GQ' => ['phrase' => 'trakt_movies_country.gq'],
			'ER' => ['phrase' => 'trakt_movies_country.er'],
			'EE' => ['phrase' => 'trakt_movies_country.ee'],
			'ET' => ['phrase' => 'trakt_movies_country.et'],
			'FK' => ['phrase' => 'trakt_movies_country.fk'],
			'FO' => ['phrase' => 'trakt_movies_country.fo'],
			'FJ' => ['phrase' => 'trakt_movies_country.fj'],
			'FI' => ['phrase' => 'trakt_movies_country.fi'],
			'FR' => ['phrase' => 'trakt_movies_country.fr'],
			'GF' => ['phrase' => 'trakt_movies_country.gf'],
			'PF' => ['phrase' => 'trakt_movies_country.pf'],
			'TF' => ['phrase' => 'trakt_movies_country.tf'],
			'GA' => ['phrase' => 'trakt_movies_country.ga'],
			'GM' => ['phrase' => 'trakt_movies_country.gm'],
			'GE' => ['phrase' => 'trakt_movies_country.ge'],
			'DE' => ['phrase' => 'trakt_movies_country.de'],
			'GH' => ['phrase' => 'trakt_movies_country.gh'],
			'GI' => ['phrase' => 'trakt_movies_country.gi'],
			'GR' => ['phrase' => 'trakt_movies_country.gr'],
			'GL' => ['phrase' => 'trakt_movies_country.gl'],
			'GD' => ['phrase' => 'trakt_movies_country.gd'],
			'GP' => ['phrase' => 'trakt_movies_country.gp'],
			'GU' => ['phrase' => 'trakt_movies_country.gu'],
			'GT' => ['phrase' => 'trakt_movies_country.gt'],
			'GN' => ['phrase' => 'trakt_movies_country.gn'],
			'GW' => ['phrase' => 'trakt_movies_country.gw'],
			'GY' => ['phrase' => 'trakt_movies_country.gy'],
			'HT' => ['phrase' => 'trakt_movies_country.ht'],
			'HM' => ['phrase' => 'trakt_movies_country.hm'],
			'VA' => ['phrase' => 'trakt_movies_country.va'],
			'HN' => ['phrase' => 'trakt_movies_country.hn'],
			'HK' => ['phrase' => 'trakt_movies_country.hk'],
			'HU' => ['phrase' => 'trakt_movies_country.hu'],
			'IS' => ['phrase' => 'trakt_movies_country.is'],
			'IN' => ['phrase' => 'trakt_movies_country.in'],
			'ID' => ['phrase' => 'trakt_movies_country.id'],
			'IR' => ['phrase' => 'trakt_movies_country.ir'],
			'IQ' => ['phrase' => 'trakt_movies_country.iq'],
			'IE' => ['phrase' => 'trakt_movies_country.ie'],
			'IL' => ['phrase' => 'trakt_movies_country.il'],
			'IT' => ['phrase' => 'trakt_movies_country.it'],
			'JM' => ['phrase' => 'trakt_movies_country.jm'],
			'JP' => ['phrase' => 'trakt_movies_country.jp'],
			'JO' => ['phrase' => 'trakt_movies_country.jo'],
			'KZ' => ['phrase' => 'trakt_movies_country.kz'],
			'KE' => ['phrase' => 'trakt_movies_country.ke'],
			'KI' => ['phrase' => 'trakt_movies_country.ki'],
			'KP' => ['phrase' => 'trakt_movies_country.kp'],
			'KR' => ['phrase' => 'trakt_movies_country.kr'],
			'KW' => ['phrase' => 'trakt_movies_country.kw'],
			'KG' => ['phrase' => 'trakt_movies_country.kg'],
			'LA' => ['phrase' => 'trakt_movies_country.la'],
			'LV' => ['phrase' => 'trakt_movies_country.lv'],
			'LB' => ['phrase' => 'trakt_movies_country.lb'],
			'LS' => ['phrase' => 'trakt_movies_country.ls'],
			'LR' => ['phrase' => 'trakt_movies_country.lr'],
			'LY' => ['phrase' => 'trakt_movies_country.ly'],
			'LI' => ['phrase' => 'trakt_movies_country.li'],
			'LT' => ['phrase' => 'trakt_movies_country.lt'],
			'LU' => ['phrase' => 'trakt_movies_country.lu'],
			'MO' => ['phrase' => 'trakt_movies_country.mo'],
			'MK' => ['phrase' => 'trakt_movies_country.mk'],
			'MG' => ['phrase' => 'trakt_movies_country.mg'],
			'MW' => ['phrase' => 'trakt_movies_country.mw'],
			'MY' => ['phrase' => 'trakt_movies_country.my'],
			'MV' => ['phrase' => 'trakt_movies_country.mv'],
			'ML' => ['phrase' => 'trakt_movies_country.ml'],
			'MT' => ['phrase' => 'trakt_movies_country.mt'],
			'MH' => ['phrase' => 'trakt_movies_country.mh'],
			'MQ' => ['phrase' => 'trakt_movies_country.mq'],
			'MR' => ['phrase' => 'trakt_movies_country.mr'],
			'MU' => ['phrase' => 'trakt_movies_country.mu'],
			'YT' => ['phrase' => 'trakt_movies_country.yt'],
			'MX' => ['phrase' => 'trakt_movies_country.mx'],
			'FM' => ['phrase' => 'trakt_movies_country.fm'],
			'MD' => ['phrase' => 'trakt_movies_country.md'],
			'MC' => ['phrase' => 'trakt_movies_country.mc'],
			'MN' => ['phrase' => 'trakt_movies_country.mn'],
			'MS' => ['phrase' => 'trakt_movies_country.ms'],
			'MA' => ['phrase' => 'trakt_movies_country.ma'],
			'MZ' => ['phrase' => 'trakt_movies_country.mz'],
			'MM' => ['phrase' => 'trakt_movies_country.mm'],
			'NA' => ['phrase' => 'trakt_movies_country.na'],
			'NR' => ['phrase' => 'trakt_movies_country.nr'],
			'NP' => ['phrase' => 'trakt_movies_country.np'],
			'NL' => ['phrase' => 'trakt_movies_country.nl'],
			'AN' => ['phrase' => 'trakt_movies_country.an'],
			'NC' => ['phrase' => 'trakt_movies_country.nc'],
			'NZ' => ['phrase' => 'trakt_movies_country.nz'],
			'NI' => ['phrase' => 'trakt_movies_country.ni'],
			'NE' => ['phrase' => 'trakt_movies_country.ne'],
			'NG' => ['phrase' => 'trakt_movies_country.ng'],
			'NU' => ['phrase' => 'trakt_movies_country.nu'],
			'NF' => ['phrase' => 'trakt_movies_country.nf'],
			'MP' => ['phrase' => 'trakt_movies_country.mp'],
			'NO' => ['phrase' => 'trakt_movies_country.no'],
			'OM' => ['phrase' => 'trakt_movies_country.om'],
			'PK' => ['phrase' => 'trakt_movies_country.pk'],
			'PW' => ['phrase' => 'trakt_movies_country.pw'],
			'PS' => ['phrase' => 'trakt_movies_country.ps'],
			'PA' => ['phrase' => 'trakt_movies_country.pa'],
			'PG' => ['phrase' => 'trakt_movies_country.pg'],
			'PY' => ['phrase' => 'trakt_movies_country.py'],
			'PE' => ['phrase' => 'trakt_movies_country.pe'],
			'PH' => ['phrase' => 'trakt_movies_country.ph'],
			'PN' => ['phrase' => 'trakt_movies_country.pn'],
			'PL' => ['phrase' => 'trakt_movies_country.pl'],
			'PT' => ['phrase' => 'trakt_movies_country.pt'],
			'PR' => ['phrase' => 'trakt_movies_country.pr'],
			'QA' => ['phrase' => 'trakt_movies_country.qa'],
			'RE' => ['phrase' => 'trakt_movies_country.re'],
			'RO' => ['phrase' => 'trakt_movies_country.ro'],
			'RU' => ['phrase' => 'trakt_movies_country.ru'],
			'RW' => ['phrase' => 'trakt_movies_country.rw'],
			'SH' => ['phrase' => 'trakt_movies_country.sh'],
			'KN' => ['phrase' => 'trakt_movies_country.kn'],
			'LC' => ['phrase' => 'trakt_movies_country.lc'],
			'PM' => ['phrase' => 'trakt_movies_country.pm'],
			'VC' => ['phrase' => 'trakt_movies_country.vc'],
			'WS' => ['phrase' => 'trakt_movies_country.ws'],
			'SM' => ['phrase' => 'trakt_movies_country.sm'],
			'ST' => ['phrase' => 'trakt_movies_country.st'],
			'SA' => ['phrase' => 'trakt_movies_country.sa'],
			'SN' => ['phrase' => 'trakt_movies_country.sn'],
			'CS' => ['phrase' => 'trakt_movies_country.cs'],
			'SC' => ['phrase' => 'trakt_movies_country.sc'],
			'SL' => ['phrase' => 'trakt_movies_country.sl'],
			'SG' => ['phrase' => 'trakt_movies_country.sg'],
			'SK' => ['phrase' => 'trakt_movies_country.sk'],
			'SI' => ['phrase' => 'trakt_movies_country.si'],
			'SB' => ['phrase' => 'trakt_movies_country.sb'],
			'SO' => ['phrase' => 'trakt_movies_country.so'],
			'ZA' => ['phrase' => 'trakt_movies_country.za'],
			'GS' => ['phrase' => 'trakt_movies_country.gs'],
			'ES' => ['phrase' => 'trakt_movies_country.es'],
			'LK' => ['phrase' => 'trakt_movies_country.lk'],
			'SD' => ['phrase' => 'trakt_movies_country.sd'],
			'SR' => ['phrase' => 'trakt_movies_country.sr'],
			'SJ' => ['phrase' => 'trakt_movies_country.sj'],
			'SZ' => ['phrase' => 'trakt_movies_country.sz'],
			'SE' => ['phrase' => 'trakt_movies_country.se'],
			'CH' => ['phrase' => 'trakt_movies_country.ch'],
			'SY' => ['phrase' => 'trakt_movies_country.sy'],
			'TW' => ['phrase' => 'trakt_movies_country.tw'],
			'TJ' => ['phrase' => 'trakt_movies_country.tj'],
			'TZ' => ['phrase' => 'trakt_movies_country.tz'],
			'TH' => ['phrase' => 'trakt_movies_country.th'],
			'TL' => ['phrase' => 'trakt_movies_country.tl'],
			'TG' => ['phrase' => 'trakt_movies_country.tg'],
			'TK' => ['phrase' => 'trakt_movies_country.tk'],
			'TO' => ['phrase' => 'trakt_movies_country.to'],
			'TT' => ['phrase' => 'trakt_movies_country.tt'],
			'TN' => ['phrase' => 'trakt_movies_country.tn'],
			'TR' => ['phrase' => 'trakt_movies_country.tr'],
			'TM' => ['phrase' => 'trakt_movies_country.tm'],
			'TC' => ['phrase' => 'trakt_movies_country.tc'],
			'TV' => ['phrase' => 'trakt_movies_country.tv'],
			'UG' => ['phrase' => 'trakt_movies_country.ug'],
			'UA' => ['phrase' => 'trakt_movies_country.ua'],
			'AE' => ['phrase' => 'trakt_movies_country.ae'],
			'GB' => ['phrase' => 'trakt_movies_country.gb'],
			'US' => ['phrase' => 'trakt_movies_country.us'],
			'UM' => ['phrase' => 'trakt_movies_country.um'],
			'UY' => ['phrase' => 'trakt_movies_country.uy'],
			'UZ' => ['phrase' => 'trakt_movies_country.uz'],
			'VU' => ['phrase' => 'trakt_movies_country.vu'],
			'VE' => ['phrase' => 'trakt_movies_country.ve'],
			'VN' => ['phrase' => 'trakt_movies_country.vn'],
			'VG' => ['phrase' => 'trakt_movies_country.vg'],
			'VI' => ['phrase' => 'trakt_movies_country.vi'],
			'WF' => ['phrase' => 'trakt_movies_country.wf'],
			'EH' => ['phrase' => 'trakt_movies_country.eh'],
			'YE' => ['phrase' => 'trakt_movies_country.ye'],
			'ZM' => ['phrase' => 'trakt_movies_country.zm'],
			'ZW' => ['phrase' => 'trakt_movies_country.zw']
		];

		$enabledCountries = \XF::options()->traktthreads_watchProviderRegions;
		if ($filterDisabled)
		{
			$countryData = array_intersect_key($countryData, array_flip($enabledCountries));
		}

		return $countryData;
	}
}