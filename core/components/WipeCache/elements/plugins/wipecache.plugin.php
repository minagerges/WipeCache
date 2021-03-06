<?php
/**
 * WipeCache
 *
 * Copyright 2014 by Mina Gerges <mina@minagerges.com>
 *
 * WipeCache is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * WipeCache is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * WipeCache; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 **/
$jspath = $modx->getOption('assets_url') . 'components/WipeCache/js/wipecache.console.js';

$eventName = $modx->event->name;
switch($eventName) {
    case 'OnBeforeManagerPageInit':
        $modx->regClientStartupScript($jspath);
        break;
    case 'OnManagerPageBeforeRender':
        break;
}
return;