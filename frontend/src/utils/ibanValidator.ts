export function validateIBAN(iban: string): boolean {
    // Eliminar espacios en blanco y convertir a mayúsculas
    iban = iban.replace(/\s+/g, '').toUpperCase();

    // Verificar longitud y estructura básica del IBAN
    if (!/^[A-Z]{2}\d{2}[A-Z0-9]{1,30}$/.test(iban)) {
        return false;
    }

    // Mover los primeros 4 caracteres al final
    iban = iban.slice(4) + iban.slice(0, 4);

    // Convertir letras a números según la tabla ASCII
    const ibanNumeric = iban.split('').map((char) => {
        if (/[A-Z]/.test(char)) {
            return char.charCodeAt(0) - 'A'.charCodeAt(0) + 10;
        } else {
            return char;
        }
    }).join('');

    // Realizar la división y verificar si el resto es 1 (módulo 97)
    if (BigInt(ibanNumeric) % BigInt(97) === BigInt(1)) {
        return true;
    } else {
        return false;
    }
}