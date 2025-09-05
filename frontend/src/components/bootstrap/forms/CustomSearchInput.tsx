import React, { useEffect, useState } from 'react'
import Modal from '../Modal'

type Props = {
  placeholder: string,
  onSearch(value: string): void
  defaultValue?: string
}

const CustomSearchInput: React.FC<Props> = ({ placeholder, onSearch, defaultValue }) => {

  const [searchValue, setSearchValue] = useState<string>(defaultValue || '');
  const [loaded, setLoaded] = useState<boolean>(false);

  useEffect(() => {
    if (loaded) {
      const delaySearch = setTimeout(() => {
        onSearch(searchValue);
      }, 1000);

      return () => clearTimeout(delaySearch);
    }

  }, [searchValue])

  useEffect(() => {
    setSearchValue(defaultValue || '');
  }, [defaultValue])

  useEffect(() => {
    setLoaded(true);
  }, [])


  return (
    <div>
      <input className='form-control form-control-solid' placeholder={placeholder} value={searchValue} onChange={(e) => setSearchValue(e.target.value)} />
    </div>
  )
}

CustomSearchInput.defaultProps = {

}

export default CustomSearchInput;
Modal.defaultProps = {
	id: undefined,
	isStaticBackdrop: false,
	isScrollable: false,
	isCentered: false,
	size: null,
	fullScreen: false,
	isAnimation: true,
	titleId: undefined,
};