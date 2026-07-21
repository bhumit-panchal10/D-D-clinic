<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dental Treatment Consent Form</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            font-family: "Times New Roman", serif;
            font-size:14px;
            color:#000;
            background:#fff;
            line-height:1.5;
        }

        .page{
            width:210mm;
            min-height:297mm;
            margin:auto;
            padding:20px;
            background:#fff;
        }

        h2,h3,h4,h5{
            margin:0;
        }

        .clinic{
            text-align:center;
            margin-bottom:5px;
        }

        .clinic h2{
            font-weight:bold;
        }

        .clinic h4{
            font-weight:bold;
            letter-spacing:1px;
        }

        .patient-info{
            margin-top:20px;
            margin-bottom:10px;
            font-size:15px;
        }

        .line{
            border-bottom:1px solid #000;
            display:inline-block;
            min-width:250px;
            padding-left:5px;
            font-weight:bold;
        }

        .case-line{
            border-bottom:1px solid #000;
            display:inline-block;
            min-width:150px;
            padding-left:5px;
            font-weight:bold;
        }

        .instruction{
            text-align:center;
            /*font-weight:bold;*/
            margin-top:10px;
            margin-bottom:15px;
        }

        /* Two column layout for clauses 1-5 (left) and 6-10 (right) */
        .clauses-columns{
            display:flex;
            gap:25px;
            align-items:flex-start;
        }

        .clauses-col{
            flex:1 1 50%;
            width:50%;
        }

        .section{
            margin-bottom:15px;
            text-align:justify;
        }

        .section-title{
            font-weight:bold;
            text-transform:uppercase;
        }

        @media print{

            body{
                margin:0;
            }

            .page{
                width:100%;
                margin:0;
                padding:15px;
            }

        }

    </style>

</head>

<body>

<div class="page">

    <div class="clinic">

        <h2>D &amp; D DENTAL CLINIC</h2>

        <h4>DENTAL TREATMENT CONSENT FORM</h4>

    </div>
    
    <div class="patient-info d-flex justify-content-between">

        <div>
        

            <strong>Patient's Name :</strong>

            <span class="line">
                {{ $patient->name }}
                {{ $patient->middle_name }}
                {{ $patient->last_name }}
            </span>

        </div>

        <div>

            <strong>Case No. :</strong>

            <span class="case-line">

                {{ $patient->case_no }}

            </span>

        </div>

    </div>

    <div class="instruction">

        Please read carefully and sign at the bottom of the form

        <br>

        મહેરબાની કરીને કાળજીપૂર્વક વાંચો અને સમજીને એના પછી નીચે સહી કરો

    </div>

    <div class="clauses-columns">

        <div class="clauses-col">

            <div class="section">

                <span class="section-title">
                    1. X-RAYS
                </span>

            </div>

            <div class="section">

                <span class="section-title">

                    2. DRUGS AND MEDICATIONS

                </span>

                I understand that antibiotics and analgesics and other medications can cause allergic reactions causing redness and swelling of tissues, pain, itching, vomiting, and/or anaphylactic shock (severe allergic reaction). I consent to the administration of local anesthesia (Novacaine), nitrous oxide analgesia or oral sedation in connection to treatment procedures. Drugs given at the time of treatment for sedative purposes or control of pain following the treatment may cause drowsiness and a lack of awareness or coordination. If instructed to do so, I will not drive or perform hazardous chores until I have recovered from the effects of these medications.

            </div>

            <div class="section">

                <span class="section-title">

                    3. CHANGES IN TREATMENT PLAN

                </span>

                I understand that during treatment it may be necessary to change or add procedures because of conditions found while working on the teeth that were not discovered during examination, the most common being root canal therapy following routine restorative procedures. I give my permission to the Dentist to make any/all changes and additions as necessary.

            </div>

            <div class="section">

                <span class="section-title">

                    4. REMOVAL OF TEETH

                </span>

                Alternatives to removal have been explained to me (root canal therapy, crowns, and periodontal surgery, etc.) and I authorize the Dentist to remove the following teeth and any others necessary for reasons in paragraph #3. I understand removing teeth does not always remove all the infection, if present, and it may be necessary to have further treatment. I understand the risks involved in having teeth removed, some of which are pain, swelling, spread of infection, dry socket, loss of feeling in my teeth, lips, tongue and surrounding tissue (Paresthesia) that can last for an indefinite period of time (days or months) or fractured jaw. I understand I may need further treatment by a specialist or even hospitalization if complications arise during or following treatment, the cost of which is my responsibility.

            </div>

            <div class="section">

                <span class="section-title">

                    5. CROWNS, BRIDGES AND CAPS

                </span>

                I understand that sometimes it is not possible to match the color of natural teeth exactly with artificial teeth. I further understand that I may be wearing temporary crowns, which may come off easily and that I must be careful to ensure that they are kept on until the permanent crowns are delivered. I realize the final opportunity to make changes in my new crown, bridge, or cap (including shape, fit, size and color) will be before cementation.

            </div>

        </div>

        <div class="clauses-col">

            <div class="section">

                <span class="section-title">

                    6. DENTURES, COMPLETE OR PARTIAL

                </span>

                I realize that full or partial dentures are artificial, constructed of plastic, metal, and/or porcelain. The problems of wearing these appliances have been explained to me, including looseness, soreness, and possible breakage. I realize the final opportunity to make changes in my new dentures (including shape, fit, size, placement, and color) will be the "teeth in wax" try-in visit. I understand that most dentures require relining approximately three to twelve months after initial placement. The cost for this procedure is not included in the initial denture fee. I understand that it is my responsibility to return for delivery of the dentures. I understand that failure to keep my delivery appointment may result in poorly fixed dentures. If a remake is required due to my delays of more than 30 days, there will be additional charges.

            </div>

            <div class="section">

                <span class="section-title">

                    7. ENDODONTIC TREATMENT (ROOT CANAL)

                </span>

                I realize there is no guarantee that root canal treatment will save my tooth, and that complications can occur from the treatment, and that occasionally metal objects are cemented in the tooth or extend through the root, which does not necessarily affect the success of the treatment. I understand that occasionally additional surgical procedures may be necessary following root canal treatment (Apicoectomy).

            </div>

            <div class="section">

                <span class="section-title">

                    8. FILLINGS

                </span>

                I understand that care must be exercised in chewing on fillings especially during the first 24 hours to avoid breakage. I understand that a more expensive filling than initially diagnosed may be required due to additional decay. I understand that significant sensitivity is a common after effect of a newly placed filling.

            </div>

            <div class="section">

                <span class="section-title">

                    9. SURGERY

                </span>

                I authorize the dentist to perform surgical procedures as per the explained treatment plan and I understand that this is an elective, urgent, or emergency procedure. I have been informed that the risks to my health if this procedure is not performed include, but are not limited to pain, infection, cyst formation, loss of bone around teeth causing their loss, and an increased risk of complications if surgery is postponed. I have been informed of any possible alternative methods of treatment should any exist.

            </div>

            <div class="section">

                <span class="section-title">

                    10. ORTHODONTICS

                </span>

                I understand that orthodontic treatment cannot completely guarantee desired results. I understand the treatment requires several appointments over a long period of time and may require extraction of healthy teeth. I understand that patient co-operation is the most important factor in completing the treatment on time. This treatment can result in complications like non vital teeth, root resorption, relapse of alignment, periodontal problems, unfavorable growth and temporomandibular joint problems.

            </div>

        </div>

    </div>

    <div class="section mt-4" style="text-align:justify;">

      I certify that I have read and understood the above and that the information given on this form is accurate to the best of my knowledge. I understand the importance of a truthful health history and that my dentist and his/her staff will rely on this information for treating me. If there is any change in my health condition, I will promptly inform the doctor on my next visit to the clinic. The doctor has explained to me the nature of the disease, treatment procedure to be carried out, complications of the treatment and fees to be paid to the doctor. I consent to the proposed treatment. Further, I understand that there are certain inherent and potential risks in any treatment or procedure and that I have been informed about them. If unexpected problems arise during the procedure, the doctor has my permission to do what is deemed necessary to correct the condition. I also give my permission for the use of my dental records, including photographs and radiographs, for the purpose of professional consultations, research, education and publication in professional journals. I understand that dentistry is not an exact science and that, therefore, reputable practitioners cannot fully guarantee results. I acknowledge that no guarantee or assurance has been made by anyone regarding the dental treatment which I have requested and authorized. I have had the opportunity to read this form and ask questions. My questions have been answered to my satisfaction. I take complete responsibility for understanding the consequences of all treatments opted by me at D & D Dental Clinic and will not blame the dentists at D & D Dental Clinic for any consequences legally. I understand all of the above information and permit the dentist to start the treatment.

    </div>
    
      <hr class="my-4">

        <table class="w-100" style="font-size:15px;">
        
            <tr>
        
                <td style="width:18%;">
                    <strong>Signature of Patient</strong>
                </td>
        
               <td style="width:52%;border-bottom:1px dotted #666;height:35px;vertical-align:bottom;">

    <img src="{{ asset('patient_signature/' . basename($signature)) }}"
         style="height:50px;">
                
                </td>
        
                <td style="width:8%;text-align:right;">
                    <strong>Date</strong>
                </td>
        
                <td style="width:22%;border-bottom:1px dotted #666;">
                    {{ date('d-m-Y') }}
                </td>
        
            </tr>
        
            <tr>
                <td colspan="4" style="height:25px;"></td>
            </tr>
        
            <tr>
        
                <td>
                    <strong>
                        Signature of Parent/Guardian if patient is a minor
                    </strong>
                </td>
        
                <td style="border-bottom:1px dotted #666;"></td>
        
                <td style="text-align:right;">
                    <strong>Date</strong>
                </td>
        
                <td style="border-bottom:1px dotted #666;"></td>
        
            </tr>
        
        </table>
        
        <hr>
        
       
</div>




</body>

</html>
