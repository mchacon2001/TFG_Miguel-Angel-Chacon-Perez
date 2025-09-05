import {utils, writeFile}  from "xlsx"

export const exportXlsx = (data: Array<any>, fileName: string) => {
    const wb = utils.book_new()
    const ws = utils.json_to_sheet(data)
    utils.book_append_sheet(wb, ws, "sheet1")
    writeFile(wb, `${fileName}.xlsx`)
}
