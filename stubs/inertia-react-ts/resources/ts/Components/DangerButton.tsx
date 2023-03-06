interface Props {
    children: React.ReactNode;
    type?: "submit" | "button" | "reset" | undefined;
    processing?: boolean;
    className?: string;
    disabled: boolean;
    onClick?: React.MouseEventHandler<HTMLElement>;
}


export default function DangerButton({ className = '', disabled, children, ...props }: Props) {
    return (
        <button
            {...props}
            className={
                `inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ${
                    disabled && 'opacity-25'
                } ` + className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}
