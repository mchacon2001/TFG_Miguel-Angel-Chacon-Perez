export const valida_cups = (CUPS: string): boolean => {
    const MathFmod = (a: number, b: number): number => {
        return Number((a - Math.floor(a / b) * b).toPrecision(8));
    };

    let status = false;
    const RegExPattern = /^ES[0-9]{16}[a-zA-Z]{2}[0-9]{0,1}[a-zA-Z]{0,1}$/;
    if (CUPS.match(RegExPattern) && CUPS !== "") {
        const CUPS_16 = CUPS.substr(2, 16);
        const control = CUPS.substr(18, 2);
        const letters = Array("T", "R", "W", "A", "G", "M", "Y", "F", "P", "D", "X", "B", "N", "J", "Z", "S", "Q", "V", "H", "L", "C", "K", "E");
        const fmodv = MathFmod(parseInt(CUPS_16), 529);
        const imod = parseInt(fmodv.toString());
        const quotient = Math.floor(imod / 23);
        const remainder = imod % 23;
        const dc1 = letters[quotient];
        const dc2 = letters[remainder];
        status = control === dc1 + dc2;
    }

    return status;
};
