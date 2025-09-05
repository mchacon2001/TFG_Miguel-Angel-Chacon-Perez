export function validateCifNif(value: string): boolean {
    const cifRegex = /^[ABCDEFGHJKLMNPQSUVW]{1}[0-9]{7}[0-9A-J]{1}$/;
    const nifRegex = /^(\d{8}[A-Z])|([XYZ]\d{7}[A-Z])$/;

    const cleanValue = value.trim().toUpperCase();

    if (cifRegex.test(cleanValue)) { 
        return true;
    }
    // Validar NIF
    if (nifRegex.test(cleanValue)) {
        const dni = parseInt(cleanValue.slice(0, -1), 10);
        const letra = cleanValue.charAt(cleanValue.length - 1);
        const letrasValidas = 'TRWAGMYFPDXBNJZSQVHLCKE';
        const letraCalculada = letrasValidas.charAt(dni % 23);
        return letra === letraCalculada;
    }

    return false;
}

export function validateNif(value: string): boolean {
    const nifRegex = /^(\d{8}[A-Z])|([XYZ]\d{7}[A-Z])$/;

    if (!value || !nifRegex.test(value)) {
        return false;
    }

    const valor = value.trim().toUpperCase();

    if (nifRegex.test(valor)) {
        const dni = parseInt(valor.slice(0, -1), 10);
        const letra = valor.charAt(valor.length - 1);
        const letrasValidas = 'TRWAGMYFPDXBNJZSQVHLCKE';
        const letraCalculada = letrasValidas.charAt(dni % 23);
        return letra === letraCalculada;
    }

    return false;
}

export function validateCif(value: string): boolean {
    const cifRegex = /^[ABCDEFGHJKLMNPQSUVW]{1}[0-9]{7}[0-9A-J]{1}$/;

    if (!value || !cifRegex.test(value)) {
        return false;
    }

    const valor = value.trim().toUpperCase();

    if (cifRegex.test(valor)) { 
        return true;
    }

    return false;
}