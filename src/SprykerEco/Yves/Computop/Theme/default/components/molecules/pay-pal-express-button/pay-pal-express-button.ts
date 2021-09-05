/* tslint:disable: no-any */
declare const paypal: any;

import Component from 'ShopUi/models/component';
import { error } from 'ShopUi/app/logger';

interface OrderData {
    orderId: string;
    merchantId: string;
    payId: string;
    clientId: string;
    currency: string;
}

const EVENT_ORDER_DATA_LOAD = 'orderDataLoad';

export default class PayPalExpressButton extends Component {
    protected head: HTMLHeadElement;
    protected paypalScript: HTMLScriptElement;
    protected orderData: OrderData;

    protected readyCallback(): void {}

    protected init(): void {
        this.head = <HTMLHeadElement>document.getElementsByTagName('head')[0];
        this.paypalScript = <HTMLScriptElement>document.createElement('script');

        this.mapEvents();
        this.fetchOrderData();
    }

    protected mapEvents(): void {
        this.addEventListener(EVENT_ORDER_DATA_LOAD, () => this.onOrderDataLoad(), { once: true });
        this.paypalScript.addEventListener('load', () => this.onPaypalScriptLoad(), { once: true });
    }

    protected fetchOrderData(): void {
        fetch(this.url, { method: 'post' })
            .then((response) => response.json())
            .then((parsedResponse) => {
                this.orderData = parsedResponse;
                this.dispatchCustomEvent(EVENT_ORDER_DATA_LOAD);
            })
            .catch(() => {
                error('Can not get order data.');
            });
    }

    protected onOrderDataLoad(): void {
        this.appendScriptTag();
    }

    protected appendScriptTag(): void {
        const paypalScriptUrl = `https://www.paypal.com/sdk/js?client-id=${this.orderData.clientId}&currency=${this.orderData.currency}&intent=authorize`;

        this.paypalScript.src = paypalScriptUrl;
        this.head.appendChild(this.paypalScript);
    }

    protected onPaypalScriptLoad(): void {
        this.initPaypaluttons();
    }

    protected initPaypaluttons(): void {
        const orderData = this.orderData;

        paypal.Buttons({
            createOrder: function (data, actions) {
                return orderData.orderId;
            },
            onApprove: function (data) {
                const rd = `MerchantId=${orderData.merchantId}&PayId=${orderData.payId}&OrderId=${orderData.orderId}`;
                window.location.href = `https://www.computop-paygate.com/cbPayPal.aspx?rd=${window.btoa(rd)}`;
            }
        }).render(this);
    }

    protected get url(): string {
        return this.getAttribute('url');
    }
}
