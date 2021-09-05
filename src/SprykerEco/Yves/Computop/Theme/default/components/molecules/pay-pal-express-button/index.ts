import register from 'ShopUi/app/registry';
export default register('pay-pal-express-button', () =>
    import(
        /* webpackMode: "lazy" */
        './pay-pal-express-button'
    ),
);
