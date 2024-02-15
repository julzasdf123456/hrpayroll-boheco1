<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIncentiveDetailsRequest;
use App\Http\Requests\UpdateIncentiveDetailsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\IncentiveDetailsRepository;
use Illuminate\Http\Request;
use Flash;

class IncentiveDetailsController extends AppBaseController
{
    /** @var IncentiveDetailsRepository $incentiveDetailsRepository*/
    private $incentiveDetailsRepository;

    public function __construct(IncentiveDetailsRepository $incentiveDetailsRepo)
    {
        $this->middleware('auth');
        $this->incentiveDetailsRepository = $incentiveDetailsRepo;
    }

    /**
     * Display a listing of the IncentiveDetails.
     */
    public function index(Request $request)
    {
        $incentiveDetails = $this->incentiveDetailsRepository->paginate(10);

        return view('incentive_details.index')
            ->with('incentiveDetails', $incentiveDetails);
    }

    /**
     * Show the form for creating a new IncentiveDetails.
     */
    public function create()
    {
        return view('incentive_details.create');
    }

    /**
     * Store a newly created IncentiveDetails in storage.
     */
    public function store(CreateIncentiveDetailsRequest $request)
    {
        $input = $request->all();

        $incentiveDetails = $this->incentiveDetailsRepository->create($input);

        Flash::success('Incentive Details saved successfully.');

        return redirect(route('incentiveDetails.index'));
    }

    /**
     * Display the specified IncentiveDetails.
     */
    public function show($id)
    {
        $incentiveDetails = $this->incentiveDetailsRepository->find($id);

        if (empty($incentiveDetails)) {
            Flash::error('Incentive Details not found');

            return redirect(route('incentiveDetails.index'));
        }

        return view('incentive_details.show')->with('incentiveDetails', $incentiveDetails);
    }

    /**
     * Show the form for editing the specified IncentiveDetails.
     */
    public function edit($id)
    {
        $incentiveDetails = $this->incentiveDetailsRepository->find($id);

        if (empty($incentiveDetails)) {
            Flash::error('Incentive Details not found');

            return redirect(route('incentiveDetails.index'));
        }

        return view('incentive_details.edit')->with('incentiveDetails', $incentiveDetails);
    }

    /**
     * Update the specified IncentiveDetails in storage.
     */
    public function update($id, UpdateIncentiveDetailsRequest $request)
    {
        $incentiveDetails = $this->incentiveDetailsRepository->find($id);

        if (empty($incentiveDetails)) {
            Flash::error('Incentive Details not found');

            return redirect(route('incentiveDetails.index'));
        }

        $incentiveDetails = $this->incentiveDetailsRepository->update($request->all(), $id);

        Flash::success('Incentive Details updated successfully.');

        return redirect(route('incentiveDetails.index'));
    }

    /**
     * Remove the specified IncentiveDetails from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $incentiveDetails = $this->incentiveDetailsRepository->find($id);

        if (empty($incentiveDetails)) {
            Flash::error('Incentive Details not found');

            return redirect(route('incentiveDetails.index'));
        }

        $this->incentiveDetailsRepository->delete($id);

        Flash::success('Incentive Details deleted successfully.');

        return redirect(route('incentiveDetails.index'));
    }
}
