import React, { FC } from 'react';
import { CardFooter, CardFooterRight } from './bootstrap/Card';
import Pagination, { PaginationItem } from './bootstrap/Pagination';
import Select from './bootstrap/forms/Select';
import Option from './bootstrap/Option';

export const PER_COUNT = {
	3: 3,
	5: 5,
	10: 10,
	25: 25,
	50: 50,
};


interface IPaginationButtonsProps {
	setPerPage(...args: unknown[]): unknown;
	setCurrentPage(...args: unknown[]): unknown;
	currentPage: number;
	perPage: number;
	lastPage: number;
	label: string;
}
const PaginationButtons: FC<IPaginationButtonsProps> = ({
	setCurrentPage,
	setPerPage,
	currentPage,
	perPage,
	lastPage,
	label,
}) => {

	const pagination = () => {
		let items = [];

		let i = currentPage - 1;
		while (i >= currentPage - 1 && i > 0) {
			items.push(
				<PaginationItem key={i} onClick={() => setCurrentPage(currentPage - 1)}>
					{i}
				</PaginationItem>,
			);

			i -= 1;
		}

		items = items.reverse();

		items.push(
			<PaginationItem key={currentPage} isActive onClick={() => setCurrentPage(currentPage)}>
				{currentPage}
			</PaginationItem>,
		);

		i = currentPage + 1;
		while (i <= currentPage + 1 && i <= lastPage) {
			items.push(
				<PaginationItem key={i} onClick={() => setCurrentPage(currentPage + 1)}>
					{i}
				</PaginationItem>,
			);

			i += 1;
		}

		return items;
	};


	return (
		<CardFooter>
			<CardFooterRight className='d-flex'>
				{lastPage > 1 && (
					<Pagination ariaLabel={label}>
						<PaginationItem
							isFirst
							isDisabled={!(currentPage - 1 > 0)}
							onClick={() => setCurrentPage(1)}
						/>
						<PaginationItem
							isPrev
							isDisabled={!(currentPage - 1 > 0)}
							onClick={() => setCurrentPage(currentPage - 1)}
						/>
						{currentPage - 1 > 1 && (
							<PaginationItem onClick={() => setCurrentPage(currentPage - 2)}>
								...
							</PaginationItem>
						)}
						{pagination()}
						{currentPage + 1 < lastPage && (
							<PaginationItem onClick={() => setCurrentPage(currentPage + 2)}>
								...
							</PaginationItem>
						)}
						<PaginationItem
							isNext
							isDisabled={!(currentPage + 1 <= lastPage)}
							onClick={() => setCurrentPage(currentPage + 1)}
						/>
						<PaginationItem
							isLast
							isDisabled={!(currentPage + 1 <= lastPage)}
							onClick={() => setCurrentPage(lastPage)}
						/>
					</Pagination>
				)}

				<Select
					size='sm'
					ariaLabel='Per'
					onChange={(e: { target: { value: string } }) => {
						setPerPage(parseInt(e.target.value, 10));
						setCurrentPage(1);
					}}
					value={perPage.toString()}>
					{Object.keys(PER_COUNT).map((i) => (
						<Option key={i} value={i}>
							{i}
						</Option>
					))}
				</Select>
			</CardFooterRight>
		</CardFooter>
	);
};

export default PaginationButtons;
