/* tslint:disable: no-any */
declare const paypal: any;
/* tslint:enable */

import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';
import AjaxLoader from 'ShopUi/components/molecules/ajax-loader/ajax-loader';

interface OrderData {
    orderId: string;
    merchantId: string;
    payId: string;
}

export default class PaypalButtons extends Component {
    protected scriptLoader: ScriptLoader;
    protected ajaxLoader: AjaxLoader;
    protected buttonsContainer: HTMLElement;
    protected orderData: OrderData;

    protected readyCallback(): void {}

    protected init(): void {
        this.scriptLoader = <ScriptLoader>Array.from(this.getElementsByClassName(`${this.jsName}__script-loader`))[0];
        this.ajaxLoader = <AjaxLoader>Array.from(this.getElementsByClassName(`${this.jsName}__ajax-loader`))[0];
        this.buttonsContainer = <HTMLElement>Array.from(this.getElementsByClassName(`${this.jsName}__buttons-container`))[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.scriptLoader.addEventListener('scriptload', () => this.onPaypalScriptLoad(), { once: true });
    }

    protected onPaypalScriptLoad(): void {
        this.initPaypaluttons();
    }

    protected initPaypaluttons(): void {
        paypal.Buttons({
            createOrder: (data, actions) => (
                fetch(this.url, { method: 'post' })
                    .then((response) => response.json())
                    .then((parsedResponse) => {
                        this.orderData = parsedResponse;
                        return this.orderData.orderId;
                    })
            ),
            onApprove: (data, actions) => {
                this.ajaxLoader.onFetching();
                const requestData = `MerchantId=${this.orderData.merchantId}&PayId=${this.orderData.payId}&OrderId=${this.orderData.orderId}`;
                window.location.href = `https://www.computop-paygate.com/cbPayPal.aspx?rd=${window.btoa(requestData)}`;
            }
        }).render(this.buttonsContainer);
    }

    protected get url(): string {
        return this.getAttribute('order-data-url');
    }
}
