<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHolidaysListRequest;
use App\Http\Requests\UpdateHolidaysListRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\HolidaysListRepository;
use Illuminate\Http\Request;
use Flash;

class HolidaysListController extends AppBaseController
{
    /** @var HolidaysListRepository $holidaysListRepository*/
    private $holidaysListRepository;

    public function __construct(HolidaysListRepository $holidaysListRepo)
    {
        $this->middleware('auth');
        $this->holidaysListRepository = $holidaysListRepo;
    }

    /**
     * Display a listing of the HolidaysList.
     */
    public function index(Request $request)
    {
        $holidaysLists = $this->holidaysListRepository->paginate(10);

        return view('holidays_lists.index')
            ->with('holidaysLists', $holidaysLists);
    }

    /**
     * Show the form for creating a new HolidaysList.
     */
    public function create()
    {
        return view('holidays_lists.create');
    }

    /**
     * Store a newly created HolidaysList in storage.
     */
    public function store(CreateHolidaysListRequest $request)
    {
        $input = $request->all();

        $holidaysList = $this->holidaysListRepository->create($input);

        Flash::success('Holidays List saved successfully.');

        return redirect(route('holidaysLists.index'));
    }

    /**
     * Display the specified HolidaysList.
     */
    public function show($id)
    {
        $holidaysList = $this->holidaysListRepository->find($id);

        if (empty($holidaysList)) {
            Flash::error('Holidays List not found');

            return redirect(route('holidaysLists.index'));
        }

        return view('holidays_lists.show')->with('holidaysList', $holidaysList);
    }

    /**
     * Show the form for editing the specified HolidaysList.
     */
    public function edit($id)
    {
        $holidaysList = $this->holidaysListRepository->find($id);

        if (empty($holidaysList)) {
            Flash::error('Holidays List not found');

            return redirect(route('holidaysLists.index'));
        }

        return view('holidays_lists.edit')->with('holidaysList', $holidaysList);
    }

    /**
     * Update the specified HolidaysList in storage.
     */
    public function update($id, UpdateHolidaysListRequest $request)
    {
        $holidaysList = $this->holidaysListRepository->find($id);

        if (empty($holidaysList)) {
            Flash::error('Holidays List not found');

            return redirect(route('holidaysLists.index'));
        }

        $holidaysList = $this->holidaysListRepository->update($request->all(), $id);

        Flash::success('Holidays List updated successfully.');

        return redirect(route('holidaysLists.index'));
    }

    /**
     * Remove the specified HolidaysList from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $holidaysList = $this->holidaysListRepository->find($id);

        if (empty($holidaysList)) {
            Flash::error('Holidays List not found');

            return redirect(route('holidaysLists.index'));
        }

        $this->holidaysListRepository->delete($id);

        Flash::success('Holidays List deleted successfully.');

        return redirect(route('holidaysLists.index'));
    }
}
