<?php


namespace Snog\Forms\Entity;


interface ExportableInterface
{
	public function getExportData(): array;
}
