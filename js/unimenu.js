jQuery(document).ready(function () {
	let submenuList = jQuery('.unimenu__header-menu ul li').find('ul');
	let submenuItem = jQuery(submenuList).closest('li');

	submenuItem.addClass('unimenu__submenu-item');
	submenuList.addClass('unimenu__submenu-list');

	jQuery(submenuItem).click(function () {
		jQuery(this).toggleClass("unimenu__show");
		jQuery(this).children(submenuList).toggleClass("unimenu__show");
	});


	jQuery(".unimenu__burger-button").click(function () {
		jQuery(".unimenu__mobile-menu-wrap").addClass('unimenu__show');
		jQuery(".unimenu__burger-overlay").addClass('unimenu__show');
		jQuery("body").addClass('unimenu__body-hidden');
	});
	jQuery(".unimenu__burger-close").click(function () {
		jQuery(".unimenu__mobile-menu-wrap").removeClass('unimenu__show');
		jQuery(".unimenu__burger-overlay").removeClass('unimenu__show');
		jQuery("body").removeClass('unimenu__body-hidden');
	});
	jQuery(".unimenu__burger-overlay").click(function () {
		jQuery(this).removeClass('unimenu__show');
		jQuery(".unimenu__mobile-menu-wrap").removeClass('unimenu__show');
		jQuery("body").removeClass('unimenu__body-hidden');
	});
});