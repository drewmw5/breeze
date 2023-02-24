interface Props {
    value: string;
    className?: string;
    children?: React.ReactNode;
}

export default function InputLabel({ value, className = '', children, ...props }: Props) {
    return (
        <label {...props} className={`block font-medium text-sm text-gray-700 dark:text-gray-300 ` + className}>
            {value ? value : children}
        </label>
    );
}
