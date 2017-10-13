define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'P2c2pPayment',
                component: 'P2c2p_P2c2pPayment/js/view/payment/method-renderer/P2c2pPayment'
            }
        );
        return Component.extend({});
    }
);