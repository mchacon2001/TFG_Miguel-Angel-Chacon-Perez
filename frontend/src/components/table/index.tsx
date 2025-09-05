import { FC, Fragment, Key } from "react";
import Button from "../bootstrap/Button";
import Dropdown, { DropdownItem, DropdownMenu, DropdownToggle } from "../bootstrap/Dropdown";
import Spinner from "../bootstrap/Spinner";
import Icon from "../icon/Icon";

interface TableAction {
  label: string;
  icon: string;
  click: (item: any) => void;
}

export interface Column {
  name: string;
  headerClass?: string;
  keyValue: string | ((item: any) => any);
  render?(element: any): JSX.Element;
  tdClick?(element: any): void;
}

interface TableProps {
  items: any;
  actions?: TableAction[];
  columns: Column[];
  isLoading?: boolean;
}

const Table: FC<TableProps> = ({ items, actions, columns, isLoading = false }) => {

  const getItemActions = (actions: TableAction[], item: any) => {
    return (
      <Dropdown>
        <DropdownToggle hasIcon={false}>
          <Button
            isLink
            icon='Menu'
            className='text-nowrap'>
          </Button>
        </DropdownToggle>
        <DropdownMenu>
          {actions.map((action) => (
            <DropdownItem key={action.label}>
              <div onClick={() => action.click(item)}>
                <Icon
                  icon={action.icon}
                />
                {action.label}
              </div>
            </DropdownItem>
          ))}
        </DropdownMenu>
      </Dropdown>
    )
  }

  const renderCell = (item: any, index: number, column: Column) => {
    if (column.keyValue) {
      if (column.render !== undefined) {
        return (
          <td key={index}>
            {column.render(item)}
          </td>
        )
      }

      if (column.tdClick !== undefined) {
        return (
          <td role="button" key={index} onClick={() => column.tdClick?.(item)}>
            <div className="text-primary">{item[typeof column.keyValue === "string" ? item[column.keyValue] : column.keyValue(item)]}</div>
          </td>
        )
      }

      return (
        <td key={index}>
          {typeof column.keyValue === "string" ? item[column.keyValue] : column.keyValue(item)}
        </td>
      )
    }
  }

  const tableColumn = (column: Column, index: Key) => {
    return (
      <th key={index} className={column.headerClass ? column.headerClass : ''}>{column.name}</th>
    );
  }

  const TableRows = (item: any, index: any, columns: Column[]) => {
    return (
      <tr key={index}>
        {columns.map((column: Column, index: number) => {
          return renderCell(item, index, column);
        })}
        {actions && (
          <Fragment>
            <td>
              {getItemActions(actions, item)}
            </td>
          </Fragment>
        )}
      </tr>
    )
  }

  return (
    <table className="table table-modern">
      <thead>
        <tr>
          {columns.map(tableColumn)}
          {actions && (
            <th key='actions'>Acciones</th>
          )}
        </tr>

      </thead>
      <tbody>
        {!isLoading ? (
          <Fragment>
            {items ? items.map((item: any, index: any) => TableRows(item, index, columns)) : <tr><td className="text-center" colSpan={columns.length + (actions ? 1 : 0)}>No hay datos</td></tr>}
          </Fragment>
        ) : <Spinner />}
      </tbody>
    </table>
  );
}

export default Table;