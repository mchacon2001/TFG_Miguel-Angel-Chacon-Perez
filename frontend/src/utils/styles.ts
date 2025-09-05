export const ReactSelectStyles = {
  control: (base: any, state: any) => ({
    ...base,
    borderRadius: '10px',
    boxShadow: state.isFocused ? '0 0 0 3px rgba(0, 0, 0, 0.3)' : '0 -1.5px 0 1px rgba(0,0,0, 0.07) !important',
    border: '0px !important',
    backgroundColor: '#f8f9fa',
  }),
  option: (provided: any, state: any) => ({
    ...provided,
    backgroundColor: state.isFocused ? 'black' : 'white',
    color: state.isFocused ? 'white' : 'black',
    '&:hover': {
      backgroundColor: 'black',
      color: 'white',
      borderColor: '#5D8540 !important'
    }
  })
}