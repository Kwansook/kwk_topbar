/**
* 2007-2025 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2025 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

document.addEventListener('DOMContentLoaded', function() {
    const topbar = document.getElementById('kwk_topbar');
    if (!topbar) return;

    const messages = topbar.querySelectorAll('.header-info');
    let currentIndex = 0;
    const defaultInterval = 5000; // 5 secondes par défaut
    const rotationInterval = parseInt(topbar.getAttribute('data-rotation-interval')) || defaultInterval;

    function showNextMessage() {
        messages.forEach((msg, index) => {
            msg.classList.toggle('active', index === currentIndex);
        });
        currentIndex = (currentIndex + 1) % messages.length;
    }

    // Afficher le premier message immédiatement
    showNextMessage();

    // Changer de message selon l'intervalle défini
    setInterval(showNextMessage, rotationInterval);

    console.log('Kwk Topbar JS loaded, rotation interval: ' + rotationInterval + 'ms');
});