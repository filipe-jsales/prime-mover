<?php
namespace GreenMainframe\GMMoverFramework\interfaces;

/*
 * This file is part of the GreenMainframe.GMMoverFramework package.
 *
 * (c) GreenMainframe Ltd
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

if (! defined('ABSPATH')) {
    exit;
}

interface PrimeMoverSystemProcessor
{
    public function primeMoverImportProcessor();
    public function primeMoverExportProcessor();
}
