/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/url',
    'Magento_Customer/js/customer-data'
], function (Component, ko, $, urlBuilder, customerData) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Klarna_Kec/mini-cart-button'
        },

        wrapper: ko.observable(''),
        kecConfiguration: ko.observable(false),

        /**
         * Initialize the component
         *
         * @returns {Promise<void>}
         */
        initialize: async function () {
            try {
                this._super();
                // eslint-disable-next-line vars-on-top
                var self = this,
                    cartItems = 0,
                    cartDataItems = customerData.get('cart')()?.items;

                if (cartDataItems && cartDataItems.length > 0) {
                    await self.fetchAndAssembleButton();
                }

                // Subscribe to the cart data, so we can fetch the button configurations when the cart data is available
                customerData.get('cart').subscribe(async function (cart) {
                    if (cart?.items) {
                        cartItems = cart.items.length;

                        // Populate the wrapper with the KEC button configurations
                        if (cartItems > 0) {
                            await self.fetchAndAssembleButton();
                        }

                        // Clear the kecConfiguration and wrapper if the cart is empty
                        if (cartItems === 0) {
                            self.clearKecConfigurationAndWrapper();
                        }
                    }
                });
            } catch (error) {
                self.clearKecConfigurationAndWrapper();
            }
        },

        fetchAndAssembleButton: async function () {
            // Don't fetch the configuration if it already exists
            if (this.kecConfiguration()) {
                return;
            }
            try {
                await this.fetchButtonConfigurations();
                if (this.kecConfiguration()) {
                    this.assembleKecWrapper();
                }
            } catch (error) {
                this.clearKecConfigurationAndWrapper();
            }
        },

        clearKecConfigurationAndWrapper: function () {
            this.kecConfiguration(false);
            this.wrapper('');
        },

        /**
         * Fetch button configurations from the server
         *
         * @returns {Promise<*>}
         */
        fetchButtonConfigurations: async function () {
            // Should always be set on true, since this is loaded each time the mini cart button is loaded.
            // Which means it can have a quote available, for instance if the user has navigated through the pages
            // Or no quote available, if the user has not added anything to the cart yet.
            let params = new URLSearchParams({
                use_existing_quote: 1
            });

            return $.ajax({
                url: urlBuilder.build(`kec/klarna/getKecConfig?${params.toString()}`),
                type: 'get',
                dataType: 'json',
                cache: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: (result) =>  result,
                error: () => false
            }).done((result) => {
                if (result && result?.isShowable) {
                    this.kecConfiguration(result);
                }
            });
        },

        /**
         * Assemble the KEC wrapper
         *
         * @returns {void}
         */
        assembleKecWrapper: function () {
            let wrapper = document.createElement('div'),
                kecContainer = document.createElement('div'),
                scriptElement = document.createElement('script');

            wrapper.classList.add('primary');

            kecContainer.id = 'kec-mini-cart-button-wrapper';
            kecContainer.style = 'width: 100%;';

            wrapper.appendChild(kecContainer);

            scriptElement.type = 'text/javascript';
            scriptElement.setAttribute('async', '');
            scriptElement.textContent = `
            window.klarnaAsyncCallback = function () {
                window.Klarna.Payments.Buttons.init({
                    client_id: '${this.kecConfiguration()['clientId']}',
                }).load(
                    {
                        container: '#${kecContainer.id}',
                        theme: '${this.kecConfiguration()['theme']}',
                        shape: '${this.kecConfiguration()['shape']}',
                        locale: '${this.kecConfiguration()['locale']}',
                        on_click: (authorize) => {
                        require(['jquery', 'mage/url'], function ($, urlBuilder) {
                            var form = new FormData();
                            form.set('additional_input', JSON.stringify({
                                    use_existing_quote: 1,
                                    auth_callback_token: '${this.kecConfiguration()['authCallbackToken']}',
                                })
                            );
                            $.ajax({
                                url: urlBuilder.build("checkout/klarna/getPayLoad"),
                                data: form,
                                type: 'post',
                                dataType: 'json',
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (result) {
                                    authorize(
                                        {
                                            collect_shipping_address: true,
                                            auto_finalize: false
                                         },
                                         result,
                                         (klarnaResult) => {
                                            require(['Klarna_Kec/js/action/workflow'], function (kec_workflow) {
                                                kec_workflow(klarnaResult, form);
                                            });
                                         });
                                    }
                                });
                            });
                        }
                    }
                )
            }();`;

            wrapper.appendChild(scriptElement);
            this.wrapper(wrapper.outerHTML);
        }
    });
});
