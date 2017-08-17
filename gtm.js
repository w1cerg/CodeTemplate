// var APP = {};

(function($, APP, undefined) {

    APP.GTM = {
        data: {},
        debug: false,
    };

    var getLinkVars = function(link) {
        if(link === undefined)
            link = window.location.href;

        var vars = {}, param;
        var params = link.slice(link.indexOf('?') + 1).split('&');
        for(var i = 0; i < params.length; i++)
        {
            param = params[i].split('=');
            vars[param[0]] = param[1];
        }
        return vars;
    };

    var linkParams = getLinkVars();
    if( linkParams['debug_gtm'] ) {
        APP.GTM.debug = true;
    }

    /**
     * Проверяет загружен ли GTM
     * @return boolean
     */
    APP.GTM.isActive = function() {
        var isInited = dataLayer
            .filter(function(value){ 
                return value.event == 'gtm.js'
            })
            .length > 0;
        var isReady = dataLayer
            .filter(function(value){ 
                return value.event == 'gtm.dom'
            })
            .length > 0;

        return (isInited && isReady);
    };

    /**
     * Отправка данных в GTM
     */
    APP.GTM.push = function(data) {
        if( !data )
            return;

        // GTM как то умудряется запускать eventCallback даже если его основной скрипт вырезан блокировщиком рекламы
        // из-за этого он запускается дважды (см конец данной функции), 
        // поэтому добавил очистку callback, для предотвращения двойного запуска
        if( data.eventCallback ) {
            var oldEventCallback = data.eventCallback;
            data.eventCallback = function() {
                oldEventCallback();
                oldEventCallback = function() {};
            };
        }

        var err = false;
        try {
            if( !dataLayer )
                window.dataLayer = [];
            dataLayer.push(data);
        } catch (err) {
            // console.log(err);
        }

        // Если не загружен GTM – возможно он блокирован и не загрузится вовсе
        // Если ошибка
        // Если есть callback
        // Cразу запускаем callback
        if( 
            (err !== false || !APP.GTM.isActive())
            && data.eventCallback
        ) {
            data.eventCallback();
        }
    };

    /**
     * Событие отправки формы
     */
    APP.GTM.formSent = function(data) {
        data.page = data.page || window.location.href;

        if( APP.GTM.debug ) {
            alert("event: form sent");
        }

        APP.GTM.push({
            'event': 'отправка формы',
            'formSent': data
        });
    };

    /**
     * Событие оформления зазаза: новый шаг заказа
     * все параметры передаются через один объект data = {step: '', products: [{}], callback: function(){} }
     * 
     * @param  step     номер этапа
     * @param  products [{ 'name': 'Triblend Android T-Shirt', 'id': '12345', 'price': '15.25', 'brand': 'Google', 'category': 'Apparel', 'variant': 'Gray', 'quantity': 1 }]
     * @param  callback событие для выполнения после отправки
     */
    APP.GTM.orderSetState = function(data) {
        if( !data || !data.step || !data.products ) {
            if( data && data.callback ) {
                data.callback();
            }
            return false;
        }
        var actionField = {'step': data.step};
        if( data.option ) {
            actionField.option = data.option;
        }

        if( APP.GTM.debug ) {
            alert("event: checkout\n\nStep: " + data.step + "\nProduct count: " + data.products.length);
        }

        APP.GTM.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField': actionField,
                    'products': data.products
                }
            },
            'eventCallback': function() {
                if( data.callback )
                    data.callback();
            }
        });
    };

    /**
     * Событие оплаты заказа
     * все параметры передаются через один объект data = {id: 'T12345', total: '2411', shipping: '140', products: [{}], callback: function(){} }
     * 
     * @param  step     номер этапа
     * @param  products [{ 'name': 'Triblend Android T-Shirt', 'id': '12345', 'price': '15.25', 'brand': 'Google', 'category': 'Apparel', 'variant': 'Gray', 'quantity': 1 }]
     * @param  callback событие для выполнения после отправки
     */
    APP.GTM.orderSetPurchase = function(data) {
        if( !data || !data.id || !data.products || !data.total || !data.shipping ) {
            return false;
        }
        var actionField = {
            'id': data.id,
            'revenue': data.total,
            'shipping': data.shipping,
        };

        if( APP.GTM.debug ) {
            alert("event: purchase\n\nTotal: " + data.total);
        }

        APP.GTM.push({
            'event': 'purchase',
            'ecommerce': {
                'purchase': {
                    'actionField': actionField,
                    'products': data.products
                }
            },
            'eventCallback': function() {
                if( data.callback )
                    data.callback();
            }
        });
    };

})(jQuery, window.APP);