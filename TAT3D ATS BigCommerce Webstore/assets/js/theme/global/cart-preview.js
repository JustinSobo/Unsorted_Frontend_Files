import $ from 'jquery';
import 'foundation-sites/js/foundation/foundation';
import 'foundation-sites/js/foundation/foundation.dropdown';
import utils from '@bigcommerce/stencil-utils';

export const CartPreviewEvents = {
    close: 'closed.fndtn.dropdown',
    open: 'opened.fndtn.dropdown',
};

export default function () {
    const loadingClass = 'is-loading';
    const $cart = $('[data-cart-preview]');
    const $cartDropdown = $cart.next();
    const $cartLoading = $('<div class="loadingOverlay"></div>');

    $('body').on('cart-quantity-update', (event, quantity) => {
        $('.cart-quantity')
            .text(quantity)
            .toggleClass('countPill--positive', quantity > 0);
    });

    // $cart.on('click', event => {
    $cart.on('click', function(event) {
        const options = {
            template: 'common/cart-preview',
        };

        // Redirect to full cart page
        //
        // https://developer.mozilla.org/en-US/docs/Browser_detection_using_the_user_agent
        // In summary, we recommend looking for the string 'Mobi' anywhere in the User Agent to detect a mobile device.
        if (/Mobi/i.test(navigator.userAgent)) {
            return event.stopPropagation();
        }

        event.preventDefault();

        $(this).parent().toggleClass('is-open');
        
        var cart_position_right = $('body').width() - ($(this).offset().left + $(this).outerWidth());
        $cartDropdown.css({"top": $(this).outerHeight(), "right": cart_position_right});

        $cartDropdown
            .addClass(loadingClass)
            .html($cartLoading);
        $cartLoading
            .show();

        utils.api.cart.getContent(options, (err, response) => {
            $cartDropdown
                .removeClass(loadingClass)
                .html(response);
            $cartLoading
                .hide();
        });
    });

    $(document).on('click', function(ev) {
        if ($(ev.target).closest('.navUser-item--cart').length === 0) {
            $cart.parent().removeClass('is-open');
        }
    });
}
