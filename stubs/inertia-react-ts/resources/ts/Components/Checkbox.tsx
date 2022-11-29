interface Props {
    name: string;
    value: string;
    handleChange: React.ChangeEventHandler<HTMLInputElement>;
}

export default function Checkbox({ name, value, handleChange }: Props) {
    return (
        <input
            type="checkbox"
            name={name}
            value={value}
            className="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
            onChange={(e) => handleChange(e)}
        />
    );
}
