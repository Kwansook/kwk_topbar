{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *}

<!-- Block kwk_topbar -->
<div id="kwk_topbar" data-rotation-interval="{$rotation_interval|escape:'html'}">
    {foreach from=$topbars item=topbar name=topbars}
        <div id="kwk_topbar-info-{$topbar.id_kwk_topbar}" class="header-info {if $smarty.foreach.topbars.first}active{/if}" style="background-color: {$topbar.background_color|escape:'html'}; color: {$topbar.text_color|escape:'html'};">
            {if $topbar.link}
                <a class="topbar-content" href="{$topbar.link|escape:'html'}" {if $topbar.target_blank}target="_blank"{/if}>
                    {$topbar.content|escape:'html'}
                </a>
            {else}
                <span class="topbar-content">
                    {$topbar.content|escape:'html'}
                </span>
            {/if}
        </div>
    {/foreach}
</div>
<!-- /Block kwk_topbar -->