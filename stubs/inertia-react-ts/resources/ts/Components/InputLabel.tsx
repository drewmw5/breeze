import React from 'react';

interface Props {
    forInput: string;
    value: string;
    className?: string;
    children?: React.ReactNode;
}

export default function InputLabel({ forInput, value, className, children }: React.FC<Props>) {
    return (
        <label htmlFor={forInput} className={`block font-medium text-sm text-gray-700 dark:text-gray-300 ` + className}>
            {value ? value : children}
        </label>
    );
}
