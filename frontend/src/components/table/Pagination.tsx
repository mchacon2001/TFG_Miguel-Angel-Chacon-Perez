/* eslint-disable jsx-a11y/anchor-is-valid */
import React, { useEffect } from 'react'
import ReactPaginate from 'react-paginate'
import ReactSelect from 'react-select'
import { KTSVG } from "../KTSVG";
import './Pagination.css';
import {ReactSelectStyles} from "../../utils/styles";


type Props = {
  pageCount: number,
  currentPage: number,
  rangeDisplayed?: number,
  totalCount?: number,
  handlePagination: Function,
  defaultPerPage?: number,
  handlePerPage: Function
}

const Pagination: React.FC<Props> = ({ pageCount, currentPage, rangeDisplayed, totalCount, handlePagination, handlePerPage, defaultPerPage }) => {

  const pageSizeOptions = [
    { value: 5, label: '5' },
    { value: 10, label: '10' },
    { value: 50, label: '50' },
    { value: 100, label: '100' },
    { value: 500, label: '500' },
    { value: 1000, label: '1000' },
  ]

  useEffect(() => {
    if (defaultPerPage) {
      handlePerPage({ value: defaultPerPage })
    }
  }, [defaultPerPage])

  return (


    <div className='d-flex row w-100'>

      <div className="col-6 d-flex align-self-center fs-6 fw-bold text-gray-400">
        <div>Nº Resultados: &nbsp; {totalCount ?? '...'}</div>
      </div>

      <div className="col-6 d-flex justify-content-end">
        <div className='col-auto d-flex align-self-center fs-6 fw-bold text-gray-700'>

          <div className='align-self-center me-2'> Nº Entradas</div>

          <ReactSelect
            menuPlacement='top'
            className={"sm"}
            styles={ReactSelectStyles}
            onChange={e => handlePerPage(e)}
            options={pageSizeOptions}
            defaultValue={defaultPerPage ? pageSizeOptions.find(e => e.value === defaultPerPage) : pageSizeOptions[0]}
          />

        </div>

        <div className='col-auto d-flex align-items-center'>
          <ReactPaginate
            pageCount={pageCount}
            pageRangeDisplayed={rangeDisplayed}
            initialPage={pageCount === 0 ? -1 : currentPage - 1}
            onPageChange={page => handlePagination(page)}
            activeLinkClassName={'active'}
            nextClassName={'page-item'}
            previousClassName={'page-item'}
            className={'pagination'}
            pageClassName={'page-item'}
            activeClassName={'active'}
            nextLabel={<KTSVG path={'/media/icons/duotune/arrows/arr023.svg'} className={""} />}
            previousLabel={<KTSVG path={'/media/icons/duotune/arrows/arr022.svg'} className={""} />}
            nextLinkClassName={'page-link'}
            previousLinkClassName={'page-link'}
            pageLinkClassName={'page-link'}
            breakLinkClassName={'page-link'}
            breakClassName={'page-item'} />
        </div>
      </div>


    </div>
  )
}

Pagination.defaultProps = {
  rangeDisplayed: 2
}

export { Pagination }
