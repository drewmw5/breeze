import React, { forwardRef, useEffect, useRef } from 'react';

interface Props {
    type?: string;
    className?: string;
    isFocused?: boolean;
    handleChange: React.ChangeEventHandler<HTMLInputElement>;
}

export default forwardRef(function TextInput(
    {
        type = 'text',
        className,
        isFocused,
        ...props }: Props, ref
) {
    const input = ref ? ref : useRef();

    useEffect(() => {
        if (isFocused) {
            input.current.focus();
        }
    }, []);

    return (
        <div className="flex flex-col items-start">
            <input
                {...props}
                type={type}
                className={
                    'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm ' +
                    className
                }
                ref={input}
            />
        </div>
    );
});
