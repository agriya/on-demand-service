angular.module('hirecoworkerApp.Constant', [])
        .constant('GENERAL_CONFIG', {
            'api_url': '/api/v1',
            'preferredLanguage': 'en',
        })
        .constant('ConstUserType', {
            'Admin': 1,
            'Customer': 2,
            'ServiceProvider': 3,
            'User': 4
        })
        .constant('CloseAccount', {
            'Delete': 1,
            'Others': 5
        })
        .constant('JobTypeId', {
            'OneTimeJob': 1,
            'PartTime': 2,
            'FullTime': 3
        })
        .constant('RequestStatusId', {
            'Open': 1,
            'Closed': 2
        })
        .constant('ConstAppintmentTimingType', {
            'SpecifyTimes': 1,
            'DuringDay': 2,
            'DuringNight': 3
        })
        .constant('ConstBookingType', {
            'TimeSlot': 1,
            'SingleDate': 2,
            'MultipleDate': 3,
            'Recurring': 4,
            'MultiHours': 5
        })
        .constant('ConstProStatus', {
            'NotPaid': 1,
            'PaidAndPendingApproval': 2,
            'Approved': 3
        })
        .constant('ConstListingStatus', {
            'Draft': 1,
            'WaitingForAdminApproval': 2,
            'Approved': 3,
            'Invisible': 4,
            'RejectedApprovalRequest': 5
        })
        .constant('ConstStatus', {
            'Confirmed': 1,
        })
         .constant('ConstTransactionTypes', {
            'BookedAndWaitingForApproval': 8,
            'BookingCanceledAndVoided': 9,
            'BookingDeclinedAndVoided': 10,
            'BookingAcceptedAndAmountMovedToEscrow': 11,
            'CompletedAndAmountMovedToWallet': 12,
            'AdminCanceledBookingAndVoided': 13,
            'BookingCanceledAndRefunded': 14, 
            'BookingCanceledAndCreditedCancellationAmount': 15,
            'PROPayment': 16,
            'TopListed': 17,
            'BonusAmount' : 18 
        })
        .constant('ConstPaymentGateways', {
            'PayPal': 1
        })
        .constant('ConstPaymentType', {
            'ThroughSite': 1,
            'PayCash': 2
        })
        .constant('ConstAppointmentStatus', {
            'PendingApproval': 1,
            'Confirmed':2,
            'Closed': 3,
            'Cancelled': 4,
            'Rejected': 5,
            'Expired': 6,
            'Present': 7,
            'Enquiry' : 8,
            'PreApproved' : 9,
            'PaymentPending' : 10,
            'CanceledByAdmin' : 11,
            'ReassignedServiceProvider' : 12,
            'Completed' : 13	
        })
        .constant('ConstWithdrawalStatuses', {
            'Pending': 1,
            'UnderProcess': 2,
            'Rejected': 3,
            'AmountTransferred': 4
        })
        .constant('ConstSocialLogin', {
            'User':10, 
            'Facebook': 1,
            'Twitter': 2,
            'Google': 3,
            'Github': 4
        })
        .constant('ConstThumb', {
            'user' : {
                'small' : {
                    'width': 28,
                    'height': 28
                },
                'medium' : {
                    'width': 110,
                    'height': 110
                },
        }
        })
        .constant('ConstPhotoThumb', {
            'photo' : {
                'medium' : {
                    'width': 250,
                    'height': 150
                },
                'large' : {
                    'width': 110,
                    'height': 110
                },
        }
    })
     .constant('ConstService', {
            'Service_1' : 1,
            'Service_2' :2,
            'Service_3':3,
            'Interview' : 19
        })
        .constant('ConstCalendarStatus',{
            "Unavailable_in_Particular_Date_And_Time" : 0, 
            "Make_a_Day_Fully_Off" : 1,
            "Unavailable_In_Every_Particular_Day_And_Time_Recursively" : 2,
            "Make_a_Day_Fully_On" : 3
        }).constant('ConstInputTypes',{
            "Text" : 1, 
            "TextArea" : 2,
            "Select" : 3,
            "Checkbox" : 4,
            "Radio" : 5,
            "MultiSelect" : 6,
            "DatePicker" : 7,
            "TimePicker" : 8,
            "DateTime" : 9
        });