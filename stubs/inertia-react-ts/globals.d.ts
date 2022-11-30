export {};

declare global {
    function route(routeName?: string, parameters?: any[] | any, absolute? = true): Function[string]

}
