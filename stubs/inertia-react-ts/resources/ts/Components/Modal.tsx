import React, { useEffect, Fragment } from 'react';
import ReactDOM from 'react-dom';
import { Transition } from '@headlessui/react';

interface Props {
    children?: React.ReactNode;
    show: boolean;
    maxWidth: string;
    closeable: boolean;
    onClose: () => void;
}

export default function Modal({ children, show = false, maxWidth = '2xl', closeable = true, onClose = () => {} }: Props) {
    useEffect(() => {
        document.body.style.overflow = show ? 'hidden' : null;
    }, [show]);

    const close = () => {
        if (closeable) {
            onClose();
        }
    };

    const closeOnEscape = (e: KeyboardEvent) => {
        if (e.key === 'Escape' && props.show) {
            close();
        }
    };

    useEffect(() => {
        document.addEventListener('keydown', closeOnEscape);
        return () => {
            document.removeEventListener('keydown', closeOnEscape);
            document.body.style.overflow = '';
        };
    }, []);

    const maxWidthClass = {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[maxWidth];

    const modalRoot = document.getElementById('modal-root');

    return ReactDOM.createPortal(
        <Transition show={show} leave="duration-200">
            <div className="fixed inset-0 z-50 px-4 py-6 overflow-y-auto sm:px-0">
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 transition-all transform" onClick={close}>
                        <div className="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"></div>
                    </div>
                </Transition.Child>

                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enterTo="opacity-100 translate-y-0 sm:scale-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100 translate-y-0 sm:scale-100"
                    leaveTo="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        className={`mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto ${maxWidthClass}`}
                    >
                        {children}
                    </div>
                </Transition.Child>
            </div>
        </Transition>,
        modalRoot
    );
}
