/* tslint:disable: no-any */
declare const paypal: any;

import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';
import { debug, error } from 'ShopUi/app/logger';

interface OrderData {
    orderId: string;
    merchantId: string;
    payId: string;
}

const EVENT_ORDER_DATA_LOAD = 'orderDataLoad';

export default class PaypalButtons extends Component {
    protected scriptLoader: ScriptLoader;
    protected buttonsContainer: HTMLElement;
    protected orderData: OrderData;

    protected readyCallback(): void {}

    protected init(): void {
        this.scriptLoader = <ScriptLoader>Array.from(this.getElementsByClassName(`${this.jsName}__script-loader`))[0];
        this.buttonsContainer = <HTMLElement>Array.from(this.getElementsByClassName(`${this.jsName}__buttons-container`))[0];

        this.mapEvents();
        this.fetchOrderData();
    }

    protected mapEvents(): void {
        this.scriptLoader.addEventListener('scriptload', () => this.onPaypalScriptLoad(), { once: true });
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

    protected onPaypalScriptLoad(): void {
        this.initPaypaluttons();
    }

    protected initPaypaluttons(): void {
        paypal.Buttons({
            createOrder: (data, actions) => {
                return fetch(this.url, { method: 'post' })
                    .then((response) => response.json())
                    .then((parsedResponse) => {
                        this.orderData = parsedResponse;
                        return this.orderData.orderId;
                    });
            },
            onApprove: (data, actions) => {
                const rd = `MerchantId=${this.orderData.merchantId}&PayId=${this.orderData.payId}&OrderId=${this.orderData.orderId}`;
                window.location.href = `https://www.computop-paygate.com/cbPayPal.aspx?rd=${window.btoa(rd)}`;
            }
        }).render(this.buttonsContainer);
    }

    protected get url(): string {
        return this.getAttribute('order-data-url');
    }
}
