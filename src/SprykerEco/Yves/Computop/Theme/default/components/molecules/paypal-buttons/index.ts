import register from 'ShopUi/app/registry';

export default register('paypal-buttons', () =>
    import(
        /* webpackMode: "lazy" */
        /* webpackChunkName: "paypal-buttons" */
        './paypal-buttons'
    ),
);
