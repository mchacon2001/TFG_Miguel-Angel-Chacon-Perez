import { JSX } from 'react';
import Swal from 'sweetalert2'
import withReactContent from 'sweetalert2-react-content'

interface IConfirmationAlert {
    title: string;
    icon: 'warning' | 'success' | 'error' | 'info' | 'question';
    onConfirm: () => void;
    text?: string;
    html?: JSX.Element;
    preConfirm?: () => void;
}

const MySwal = withReactContent(Swal);

export const handleConfirmationAlert = async (props: IConfirmationAlert) => {

    let options: any = {
        heightAuto: false,
    };

    if (props.text) {
        options.text = props.text;
    }

    if (props.html) {
        options.html = props.html;
    }

    if (props.preConfirm) {
        options.preConfirm = props.preConfirm;
    }

    options = {
        ...options,
        title: props.title,
        icon: props.icon,
        showCancelButton: true,
        focusCancel: true,
        confirmButtonColor: '#5D8540',
        cancelButtonColor: '#e5133d',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
    }

    return MySwal.fire(options).then((result) => {
        if (result.isConfirmed) {
            props.onConfirm();
        }
    });
};