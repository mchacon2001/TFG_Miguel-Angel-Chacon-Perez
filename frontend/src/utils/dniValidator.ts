
// This function validate DNI and CIF
export const validateDNI = (cif: string) => {

    //Quitamos el primer caracter y el ultimo digito
	var valueCif=cif.substr(1,cif.length-2);
 
	var suma=0;
 
	//Sumamos las cifras pares de la cadena
	for(let i=1;i<valueCif.length;i=i+2)
	{
		suma=suma+parseInt(valueCif.substr(i,1));
	}
 
	var suma2=0;
 
	//Sumamos las cifras impares de la cadena
	for(let i=0;i<valueCif.length;i=i+2)
	{
		let result: number = parseInt(valueCif.substr(i,1))*2;
		if(String(result).length==1)
		{
			// Un solo caracter
			suma2=suma2+result;
		}else{
			// Dos caracteres. Los sumamos...
			suma2=suma2+parseInt(String(result).substr(0,1))+parseInt(String(result).substr(1,1));
		}
	}
 
	// Sumamos las dos sumas que hemos realizado
	suma=suma+suma2;
 
	let unidad: number = parseInt(String(suma).substr(1,1))
	unidad=10-unidad;
 
	var primerCaracter=cif.substr(0,1).toUpperCase();
 
	if(primerCaracter.match(/^[FJKNPQRSUVW]$/))
	{
		//Empieza por .... Comparamos la ultima letra
		if(String.fromCharCode(64+unidad).toUpperCase()==cif.substr(cif.length-1,1).toUpperCase())
			return true;
	}else if(primerCaracter.match(/^[XYZ]$/)){
		//Se valida como un dni
		let newcif = "";
		if(primerCaracter=="X")
			newcif=cif.substr(1);
		else if(primerCaracter=="Y")
			newcif="1"+cif.substr(1);
		else if(primerCaracter=="Z")
			newcif="2"+cif.substr(1);
		return validateOnlyDNI(newcif);
	}else if(primerCaracter.match(/^[ABCDEFGHLM]$/)){
		//Se revisa que el ultimo valor coincida con el calculo
		if(unidad==10)
			unidad=0;
		if(cif.substr(cif.length-1,1)==String(unidad))
			return true;
	}else{
		//Se valida como un dni
		return validateOnlyDNI(cif);
	}
	return false;

};


export const validateOnlyDNI = (value: string) => {

    if (value) {
        const dniRegex = /^(\d{8})([A-Z])$/;
        const matches = value.match(dniRegex);
        if (matches) {
            const dniNumbers = matches[1];
            const dniLetter = matches[2];
            const letters = "TRWAGMYFPDXBNJZSQVHLCKE";
            const index = parseInt(dniNumbers, 10) % 23;
            return letters.charAt(index) === dniLetter.toUpperCase();
        }
    }
}
