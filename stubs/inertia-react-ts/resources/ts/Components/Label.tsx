import React from 'react';

interface Props {
    forInput: string;
    value: string;
    className?: string;
    children?: React.ReactNode;
}


const Label: React.FC<Props> = ({ forInput, value, className, children }: Props) => {
    return (
        <label htmlFor={forInput} className={`block font-medium text-sm text-gray-700 ` + className}>
            {value ? value : { children }}
        </label>
    );
}

export default Label;
