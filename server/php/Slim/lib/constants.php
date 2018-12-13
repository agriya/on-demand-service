<?php
/**
 * Constants configurations
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Base
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Constants;

class ConstUserTypes
{
    const Admin = 1;
    const Customer = 2;
    const ServiceProvider = 3;
    const User = 4;
}
class SocialLogins
{
    const Twitter = 1;
    const Facebook = 2;
    const GooglePlus = 3;
}
class JWT
{
    const JWTTOKENEXPTIME = 6000;
}
class ConstDeviceType
{
    const Android = 1;
    const iPhone = 2;
}
class UserCashWithdrawStatus
{
    const Pending = 1;
    const UnderProcess = 2;
    const Rejected = 3;
    const Failed = 4;
    const Approved = 5; 
}
class Security
{
    const salt = '2dd8271e35f1f7ee5aec1ca909d46dce7c6aec7e';
}
class TransactionType
{
    const AmountAddedToWallet = 1;
    const AdminAddedAmountToUserWallet = 2;
    const AdminDeductedAmountToUserWallet = 3;
    const WithdrawRequested = 4;
    const WithdrawRequestApproved = 5;
    const WithdrawRequestRejected = 6;
    const WithdrawRequestCommission = 7;
    const BookedAndWaitingForApproval = 8;
    const BookingCanceledAndVoided = 9;
    const BookingDeclinedAndVoided = 10;
    const BookingAcceptedAndAmountMovedToEscrow = 11;
    const CompletedAndAmountMovedToWallet = 12;
    const AdminCanceledBookingAndVoided = 13;
    const BookingCanceledAndRefunded = 14;
    const BookingCanceledAndCreditedCancellationAmount = 15;
    const PROPayment = 16;
    const TopListed = 17;
    const BonusAmount = 18;
}
class ConstAppointmentStatus
{
    const PendingApproval = 1;
    const Approved = 2;
    const Closed = 3;
    const Cancelled = 4;
    const Rejected = 5;
    const Expired = 6;
    const Present = 7;
    const Enquiry = 8;
    const PreApproved = 9;
    const PaymentPending = 10;
    const CanceledByAdmin = 11;
    const ReassignedServiceProvider = 12;
    const Completed = 13;
}
class PaymentGateways
{
    const PayPal = 1;
}
class ConstBookingOption
{
    const TimeSlot = 1;
    const SingleDate = 2;
    const MultipleDate = 3;
    const Recurring = 4;
    const MultiHours = 5;
}
class ConstListingStatus
{
    const Draft = 1;
    const WaitingForAdminApproval = 2;
    const Approved = 3;
    const MarkedAsInvisibleByProvider = 4;
    const RejectedApprovalRequest = 5;
}
class ConstAppointmentModificationType
{
    const UnavailableParticularDateAndTime = 0;
    const MakeADayFullyOff = 1;
    const UnavailableInEveryParticularDayAndTime = 2;
    const MakeADayFullyOn = 3;    
}
class ConstAppointmentSettingType
{
    const SameForAll = 0;
    const IndividualDays = 1;
}
class ConstProUser
{
    const NotPaid = 1;
    const PaidAndPendingApproval = 2;
    const Approved = 3;    
}
class ConstJobType
{
    const OneTimeJob = 1;
    const PartTime = 2;
    const FullTime = 3;    
}
class ConstRequestStatus
{
    const Open = 1;
    const Closed = 2;
}
class ConstAppointmentTimingType
{
    const SpecificTime = 1;
    const DuringTheDay = 2;
    const DuringTheNight = 3;
}
class ConstPaymentMode
{
    const ThroughSite = 1;
    const PayCash = 2;
}
class ConstInterview
{
    const Interview = '19';
}