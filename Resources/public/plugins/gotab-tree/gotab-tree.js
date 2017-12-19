/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$('.Tree').on('click', '.Branch', function (evt) {
	if ($(evt.target).closest('.Tree').hasClass('Searching')) {
		return;
	}
	evt.stopPropagation(); // avoid to open/close super-branches
	if (!$(evt.target).closest('.Leaf').length) {
		$(this).toggleClass('opened');
	}
});
