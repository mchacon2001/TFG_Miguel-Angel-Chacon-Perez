import { ChangeEvent, FC, Fragment, useState } from "react";
import Input from "./bootstrap/forms/Input";
import Icon from "./icon/Icon";

interface ISearchbarProps {
    searchFunction: (searchText: string) => void;
}

const Searchbar: FC<ISearchbarProps> = ({searchFunction}) => {

    const [searchInput, setSearchInput] = useState<string>('');

    const handleTextChange = (e: ChangeEvent<HTMLInputElement>) => {
    setSearchInput(e.target.value);
    setTimeout(() => {
        searchFunction(e.target.value);
    }, 500);
    };
    
    return (
        <Fragment>
            <label className='border-0 bg-transparent cursor-pointer' htmlFor='searchInput'>
                <Icon icon='Search' size='2x' color='primary' />
            </label>
            <Input onChange={handleTextChange} value={searchInput} id='searchInput' type='search' className='w-auto' placeholder='Buscar...' autoComplete='off' />
        </Fragment>
    )   
}

export default Searchbar;