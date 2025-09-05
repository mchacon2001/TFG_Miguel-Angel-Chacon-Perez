/* eslint-disable jsx-a11y/anchor-is-valid */
import CustomSearchInput from './../bootstrap/forms/CustomSearchInput';
import './CustomTable.css';
import React, { ChangeEvent, Key, useEffect, useState } from 'react'
import Spinner from './../bootstrap/Spinner';
import { KTSVG } from '../KTSVG'
import { Pagination } from './Pagination';
import clsx from "clsx";
import { isEmptyArray } from 'formik';

interface Column {
  name: string,
  sortable?: boolean,
  keyValue?: string,
  className?: string,
  cellClassName?: string,
  ordered?: "ASC" | "DESC",
  render?(element: any): JSX.Element,
  isActionCell?: boolean,
  isVisible?: boolean,
  sortColumn?(keyValue: string, order: "asc" | "desc"): void
}

interface SearchInput {
  placeholder: string,
  onSearch(value: string): void
}

type Columns = Array<Column>;


interface Action {
  title: string,
  iconPath?: string,
  iconColor?: string,
  iconColorFunction?(element: any): string,
  description?: string,
  hide?(element: any): boolean,
  route?(element: any): string 
  buttonType?: "normal" | "icon" | "link",
  additionalClasses?: string,
  callback?(element: any): void
}

type Actions = Array<Action>;

interface PaginationProps {
  pageCount: number,
  currentPage: number,
  pageSize: number,
  totalCount?: number,
  handlePagination: Function
  handlePerPage: Function
}

type Props = {
  className: string,
  tableClassName?: string,
  title?: string,
  subtitle?: string,
  data: Array<any> | null,
  selectableItems?: boolean,
  onSelectAllItems?(checked: boolean): void,
  defaultLimit?: number,
  defaultOrder?: any,
  onChangeCheckedItems?(id: string, event: ChangeEvent<HTMLInputElement>, element: any): void,
  columns: Columns
  toolbar?: boolean,
  actions?: Actions | null,
  startElementToShow?: number,
  pagination?: boolean
  paginationData?: PaginationProps,
  onClickRow?(id: string): void,
  searchInput?: SearchInput
  empty_message?: string
}


const DEFAULT_COLUMN_WIDTH = "min-w-100px";

const CustomTable: React.FC<Props> = ({ className, title, subtitle, data, selectableItems, onSelectAllItems, onChangeCheckedItems, toolbar, columns, actions, tableClassName, defaultLimit, defaultOrder, startElementToShow, pagination, paginationData, onClickRow, searchInput, empty_message }) => {

  const [confColumns, setConfColumns] = useState(columns);

  const [sortedBy, setSortedBy] = useState<Key | null>(
    defaultOrder !== undefined ? confColumns.filter((column: Column) => column.isVisible === undefined || (column.isVisible !== undefined && column.isVisible))
      .findIndex((column: Column) => column.keyValue === defaultOrder[0]?.field && defaultOrder[0]?.order === "asc")
      :
      null);

  useEffect(() => {
    if (defaultOrder !== undefined) {
      setSortedBy(confColumns.filter((column: Column) => column.isVisible === undefined || (column.isVisible !== undefined && column.isVisible))
        .findIndex((column: Column) => column.keyValue === defaultOrder[0]?.field && defaultOrder[0]?.order === "asc"));
    }
  }, [defaultOrder]);

  const [selectedAll, setSelectedAll] = useState<boolean>(false);

  const [selectedItems, setSelectedItems] = useState<any[]>([]);

  useEffect(() => {
    setSelectedItems([]);
  }, [data]);

  const tableColumn = (column: Column, index: Key) => {

    let className = column.className ?? DEFAULT_COLUMN_WIDTH;
    let columnName = column.name;
    let sortable = column.sortable ?? false;
    let checkboxColumn = null;

    if (selectableItems === true && index === 0) {
      checkboxColumn = (
        <th key={`table-checkbox` + index} className={``}>
          <input
            key={`table-checkbox-check-all` + index}
            className="form-check-input"
            type="checkbox"
            id={`table-checkbox-select-all`}
            checked={selectedAll}
            onChange={(event: ChangeEvent<HTMLInputElement>) => {
              let checked = event.target.checked;

              setSelectedAll(checked);

              if (checked) {
                setSelectedItems(data?.map((item: any) => item.id) ?? []);
              } else {
                setSelectedItems([]);
              }

              onSelectAllItems && onSelectAllItems(checked);

            }}
          />
        </th>
      );
    }


    return (
      <React.Fragment key={'column-' + index}>
        {checkboxColumn}
        <th key={index} className={className}>{columnName}
          {sortable === true && (
            renderSortArrows(index, column)
          )}</th>
      </React.Fragment>

    )
  }


  /**
   * Render cell with configured props.
   *
   * @param element
   * @param column
   * @returns
   */
  const renderCell = (element: any, index: Key, column: Column) => {

    if (column.isActionCell === true && actions !== undefined && actions !== null) {
      return renderActions(element, index, actions);
    }

    if (column.keyValue) {
      if (column.render !== undefined) {
        return (
          <td key={index + '-cell'}>
            {column.render(element)}
          </td>
        )
      }

      return (
        <td key={index + '-cell'}>
          <div key={index + '-cell-field'} className={column.cellClassName}>
            {element[column.keyValue]}
          </div>
        </td>
      )

    }




    throw new Error(`Column ${column.name} is not defined correctly.`)

  }


  const callToAction = (action: Action, element: any) => {
    if (action.callback) {
      action.callback(element);
    }
  }


  const renderSortArrows = (index: Key, column: Column) => {

    return (
      <a
        key={index + '-' + column.name}
        onClick={() => {
          if (column.sortColumn !== undefined && column.keyValue != undefined) {
            if (sortedBy === index) {
              setSortedBy(null)
              column.sortColumn(column.keyValue, "desc")
            } else {
              setSortedBy(index);
              column.sortColumn(column.keyValue, "asc")
            }
          }
        }}
        data-toogle="tooltip"
        className='btn btn-icon btn-active-color-primary p-0 ms-1'
      >
        <KTSVG path={'/media/icons/duotune/arrows/' + (sortedBy === index ? 'arr073' : 'arr072') + '.svg'} className='svg-icon-3' />
      </a>)
  }


  const renderActions = (element: any, index: Key, actions: Array<Action>): JSX.Element => {

    return (
      <td key={index + '-cell'}>
        <div className='d-flex justify-content-end flex-shrink-0'>
          {actions.filter((action: Action) => action.hide === undefined || action.hide(element) === false)?.map((action: Action, index: number) => {
            return actionCell(action, index, element);
          })}
        </div>
      </td>
    );
  }

  const actionCell = (action: Action, index: Key, element: any) => {

    return (
      <a
        key={index + '-action-' + element.id}
        href={action.buttonType === "link" && action.route !== undefined ? action.route(element) : "#"}
        onClick={(event) => {
          if (action.buttonType !== "link") {
            event.preventDefault();
          }
          if (action.buttonType === "link") {
            return false;
          }
          callToAction(action, element);

          return false;
        }}
        title={action.description}
        data-toogle="tooltip"
        className={
          clsx({
            'me-1': action.buttonType === "icon",
            'btn btn-sm btn-primary me-2': action.buttonType === "normal"
          }) + " " + action.additionalClasses
        }
      >
        {
          (action.buttonType === "icon" || action.buttonType === undefined) && (<KTSVG key={index + '-action-icon-' + element.id} path={action.iconPath ?? ""} className={`svg-icon-3 ${action.iconColor || (action.iconColorFunction ? action.iconColorFunction(element) : "")}`} />)
        }
        {
          action.buttonType === "normal" && (<span key={index + '-action-text-' + element.id} className="btn-label">{action.title.toUpperCase()}</span>)
        }
        {
          action.buttonType === "link" && (<KTSVG key={index + '-action-icon-' + element.id} path={action.iconPath ?? ""} className={`svg-icon-3 ${action.iconColor || (action.iconColorFunction ? action.iconColorFunction(element) : "")}`} />)
        }

      </a>
    );
  }

  const renderCheckbox = (selectableItems: boolean = false, element: any) => {
    if (selectableItems === true) {
      return (
        <td key={'cell-checkbox-' + element.id}>
          <div>
            <div key={'field-checkbox-' + element.id} className="form-check">
              <input
                key={`checkbox-${element.id}`}
                className="form-check-input"
                type="checkbox"
                id={`checkbox-${element.id}`}
                checked={selectedItems.includes(element.id) || selectedAll && selectedItems.includes(element.id)}
                onChange={(event: ChangeEvent<HTMLInputElement>) => {

                  setSelectedItems((prev) => {
                    if (event.target.checked) {
                      return [...prev, element.id];
                    } else {
                      return prev.filter((item) => item !== element.id);
                    }
                  });

                  onChangeCheckedItems && onChangeCheckedItems(element.id, event, element);
                }}
              />
            </div>
          </div>
        </td>
      )
    }
  }


  const tableRow = (element: any, index: number, columns: Columns) => {

    return (
      <tr key={index} onClick={() => onClickRow !== undefined ? onClickRow(element.id) : null} >
        {renderCheckbox(selectableItems ?? false, element)}
        {columns
          .filter(column => column.isVisible === undefined || column.isVisible)
          .map((column: Column, index: number) => renderCell(element, index, column))
        }
      </tr>
    );

  }

  return (
    <div className={`custom-table table-responsive p-2 ${className}`}>

      {searchInput !== undefined && (
        <div className="row me-5 mt-5 d-flex justify-content-end">
          <div className="col-md-3">
            <CustomSearchInput placeholder={searchInput.placeholder} onSearch={searchInput.onSearch} />
          </div>
        </div>
      )}


      <table className={'table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4' + tableClassName}>

        <thead>
          <tr className='fw-bolder text-muted'>
            {confColumns
              .filter((column: Column) => column.isVisible === undefined || (column.isVisible !== undefined && column.isVisible)) 
              .map(tableColumn)}
          </tr>
        </thead>

        <tbody>
          {data !== null && data.filter((value: any, index: number) => startElementToShow !== undefined && index >= startElementToShow).map((element: any, index: number) => tableRow(element, index, columns))}
          {isEmptyArray(data) && <tr><td colSpan={confColumns.length} className='text-center'>{empty_message ? empty_message : "No hay resultados"}</td></tr>}
          {data === null && (<tr><td colSpan={confColumns.length} className='text-center'><Spinner isGrow /></td></tr>)}
        </tbody>
      </table>

      {pagination === true && paginationData !== undefined && paginationData !== null && (

        <Pagination currentPage={paginationData.currentPage} pageCount={paginationData.pageCount} totalCount={paginationData.totalCount}
          handlePagination={paginationData.handlePagination} handlePerPage={paginationData.handlePerPage} defaultPerPage={defaultLimit} />

      )}

    </div>
  )
}

CustomTable.defaultProps = {
  tableClassName: '',
  startElementToShow: 0
}

export { CustomTable }
