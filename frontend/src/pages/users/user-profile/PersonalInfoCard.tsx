import { FC, Fragment } from "react";
import Card, { CardBody } from "../../../components/bootstrap/Card";
import Icon from "../../../components/icon/Icon";

interface PersonalInfoCardProps {
    name: string;
    targetWeight?: number | string | null;
    sex?: string | null;
    weight?: number | null;
    height?: number | null;
    bodyFat?: number | null;
    bmi?: number | null;
    physicalUpdated?: boolean;
    mentalUpdated?: boolean;
    toGainMuscle?: boolean;
    toLoseWeight?: boolean;
    toMaintainWeight?: boolean;
    toImprovePhysicalHealth?: boolean;
    toImproveMentalHealth?: boolean;
    fixShoulder?: boolean;
    fixKnees?: boolean;
    fixBack?: boolean;
    rehab?: boolean;
    dailyCaloriesConsumed?: number;
    shouldHideAdvancedInfo?: boolean;
}

const PersonalInfoCard: FC<PersonalInfoCardProps> = ({
    name,
    targetWeight,
    sex,
    weight,
    height,
    bodyFat,
    bmi,
    physicalUpdated = false,
    mentalUpdated = false,
    toGainMuscle,
    toLoseWeight,
    toMaintainWeight,
    toImprovePhysicalHealth,
    toImproveMentalHealth,
    fixShoulder,
    fixKnees,
    fixBack,
    rehab,
    dailyCaloriesConsumed = 0,
    shouldHideAdvancedInfo = false,
}) => {
    let sexLabel = "No especificado";
    if (sex === "male") sexLabel = "Masculino";
    else if (sex === "female") sexLabel = "Femenino";
    else if (sex) sexLabel = sex;

    const calculateIMC = () => {
        if (weight && height) {
            const heightInMeters = height / 100;
            const imc = weight / (heightInMeters * heightInMeters);
            return imc;
        }
        return null;
    };

    const getIMCCategory = (imc: number) => {
        if (imc < 18.5) return { category: "Bajo peso", color: "text-warning" };
        if (imc < 25) return { category: "Normal", color: "text-success" };
        if (imc < 30) return { category: "Sobrepeso", color: "text-warning" };
        return { category: "Obesidad", color: "text-danger" };
    };

    const currentIMC = calculateIMC();
    const imcData = currentIMC ? getIMCCategory(currentIMC) : null;

    const bodyFatDisplay =
        bodyFat !== null && bodyFat !== undefined && !isNaN(Number(bodyFat))
            ? `${Number(bodyFat).toFixed(2)} %`
            : 'No especificado';

    const activeFlags = [];
    if (toGainMuscle) activeFlags.push({ label: 'Ganar músculo', color: 'primary' });
    if (toLoseWeight) activeFlags.push({ label: 'Perder peso', color: 'warning' });
    if (toMaintainWeight) activeFlags.push({ label: 'Mantener peso', color: 'success' });
    if (toImprovePhysicalHealth) activeFlags.push({ label: 'Mejorar salud física', color: 'info' });
    if (toImproveMentalHealth) activeFlags.push({ label: 'Mejorar salud mental', color: 'secondary' });
    if (fixShoulder) activeFlags.push({ label: 'Corregir hombros', color: 'danger' });
    if (fixKnees) activeFlags.push({ label: 'Corregir rodillas', color: 'danger' });
    if (fixBack) activeFlags.push({ label: 'Corregir espalda', color: 'danger' });
    if (rehab) activeFlags.push({ label: 'Rehabilitación', color: 'dark' });

    const calculateDailyCalorieGoal = () => {
        if (!bmi) return null;
        
        const tdee = Number(bmi) * 1.55;
        
        if (toLoseWeight) {
            return Math.round(tdee * 0.8);
        } else if (toMaintainWeight) {
            return Math.round(tdee * 1.0);
        } else if (toGainMuscle) {
            return Math.round(tdee * 1.15);
        }
        
        return Math.round(tdee);
    };

    const dailyCalorieGoal = calculateDailyCalorieGoal();
    const caloriesRemaining = dailyCalorieGoal ? dailyCalorieGoal - dailyCaloriesConsumed : 0;
    const isOverGoal = caloriesRemaining < 0;

    return (
        <Fragment>
            <Card className='shadow-3d-primary'>
                <CardBody>
                    <div className='row g-5 py-3'>
                        <div className='col-12'>
                            <div className='row g-3'>
                                <div className='col-12'>
                                    <div className='d-flex align-items-center'>
                                        <div className='flex-shrink-0'>
                                            <Icon icon='Person' size='2x' color='primary' />
                                        </div>
                                        <div className='flex-grow-1 ms-3'>
                                            <div className='text-muted'>
                                                Nombre
                                            </div>
                                            <div className='fw-bold fs-5 mb-0'>
                                                {name}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {!shouldHideAdvancedInfo && (
                                    <>
                                        <div className='col-6'>
                                            <div className='d-flex align-items-center'>
                                                <div className='flex-shrink-0'>
                                                    <Icon icon='Flag' size='2x' color='primary' />
                                                </div>
                                                <div className='flex-grow-1 ms-3'>
                                                    <div className='text-muted'>
                                                        Peso objetivo
                                                    </div>
                                                    <div className='fw-bold mb-0'>
                                                        {targetWeight ?? 'No especificado'}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div className='col-6'>
                                            <div className='d-flex align-items-center'>
                                                <div className='flex-shrink-0'>
                                                    <Icon icon='Wc' size='2x' color='primary' />
                                                </div>
                                                <div className='flex-grow-1 ms-3'>
                                                    <div className='text-muted'>
                                                        Sexo
                                                    </div>
                                                    <div className='fw-bold mb-0'>
                                                        {sexLabel}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div className='col-6'>
                                            <div className='d-flex align-items-center'>
                                                <div className='flex-shrink-0'>
                                                    <Icon icon='FitnessCenter' size='2x' color='primary' />
                                                </div>
                                                <div className='flex-grow-1 ms-3'>
                                                    <div className='text-muted'>
                                                        Peso actual
                                                    </div>
                                                    <div className='fw-bold mb-0'>
                                                        {weight !== null && weight !== undefined
                                                            ? `${weight} kg`
                                                            : 'No especificado'}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className='col-6'>
                                            <div className='d-flex align-items-center'>
                                                <div className='flex-shrink-0'>
                                                    <Icon icon='Height' size='2x' color='primary' />
                                                </div>
                                                <div className='flex-grow-1 ms-3'>
                                                    <div className='text-muted'>
                                                        Altura (cm)
                                                    </div>
                                                    <div className='fw-bold mb-0'>
                                                        {height !== null && height !== undefined
                                                            ? `${height} cm`
                                                            : 'No especificado'}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className='col-6'>
                                            <div className='d-flex align-items-center'>
                                                <div className='flex-shrink-0'>
                                                    <Icon icon='PieChart' size='2x' color='primary' />
                                                </div>
                                                <div className='flex-grow-1 ms-3'>
                                                    <div className='text-muted'>
                                                        Índice de Masa Corporal
                                                    </div>
                                                    <div className='fw-bold mb-0'>
                                                        {currentIMC ? (
                                                            <div>
                                                                <span>{currentIMC.toFixed(1)} kg/m²</span>
                                                                {imcData && (
                                                                    <span className={`ms-2 ${imcData.color}`}>
                                                                        ({imcData.category})
                                                                    </span>
                                                                )}
                                                            </div>
                                                        ) : (
                                                            'No especificado'
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className='col-6'>
                                            <div className='d-flex align-items-center'>
                                                <div className='flex-shrink-0'>
                                                    <Icon icon='Calculate' size='2x' color='primary' />
                                                </div>
                                                <div className='flex-grow-1 ms-3'>
                                                    <div className='text-muted'>
                                                        BMR <span className="fst-italic">(Calorías diarias para mantener funciones vitales)</span>
                                                    </div>
                                                    <div className='fw-bold mb-0'>
                                                        {bmi ?? 'No especificado'} kcal
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className='col-6'>
                                            <div className='d-flex align-items-center'>
                                                <div className='flex-shrink-0'>
                                                    <Icon icon='FitnessCenter' size='2x' color={physicalUpdated ? 'success' : 'secondary'} />
                                                </div>
                                                <div className='flex-grow-1 fs-5 ms-3'>
                                                    <div className='text-muted'>
                                                        Estado físico
                                                    </div>
                                                    <div className='fw-bold mb-0'>
                                                        <span className={physicalUpdated ? "text-success" : "text-secondary"}>
                                                            {physicalUpdated ? "Actualizado" : "Sin actualizar"}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className='col-6'>
                                            <div className='d-flex align-items-center'>
                                                <div className='flex-shrink-0'>
                                                    <Icon icon='Psychology' size='2x' color={mentalUpdated ? 'success' : 'secondary'} />
                                                </div>
                                                <div className='flex-grow-1 fs-5 ms-3'>
                                                    <div className='text-muted'>
                                                        Estado mental
                                                    </div>
                                                    <div className='fw-bold mb-0'>
                                                        <span className={mentalUpdated ? "text-success" : "text-secondary"}>
                                                            {mentalUpdated ? "Actualizado" : "Sin actualizar"}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {dailyCalorieGoal && (
                                            <div className='col-12'>
                                                <div className='row g-3'>
                                                    <div className='col-6'>
                                                        <div className='d-flex align-items-center'>
                                                            <div className='flex-shrink-0'>
                                                                <Icon icon='LocalFireDepartment' size='2x' color='warning' />
                                                            </div>
                                                            <div className='flex-grow-1 ms-3'>
                                                                <div className='text-muted'>
                                                                    Objetivo Calórico Diario
                                                                </div>
                                                                <div className='fw-bold mb-0'>
                                                                    {dailyCalorieGoal} kcal
                                                                    {toLoseWeight && <span className="text-warning ms-2">(Déficit)</span>}
                                                                    {toMaintainWeight && <span className="text-success ms-2">(Mantenimiento)</span>}
                                                                    {toGainMuscle && <span className="text-primary ms-2">(Superávit)</span>}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className='col-6'>
                                                        <div className='d-flex align-items-center'>
                                                            <div className='flex-shrink-0'>
                                                                <Icon icon='RestaurantMenu' size='2x' color='info' />
                                                            </div>
                                                            <div className='flex-grow-1 ms-3'>
                                                                <div className='text-muted'>
                                                                    Calorías Restantes
                                                                </div>
                                                                <div className='fw-bold mb-0'>
                                                                    <span className={isOverGoal ? 'text-danger' : 'text-success'}>
                                                                        {Math.abs(caloriesRemaining)} kcal {isOverGoal ? 'por encima' : 'restantes'}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div className='row g-2 mt-3 mb-3'>
                                                    <div className='col-4'>
                                                        <div className='text-center p-2 border rounded'>
                                                            <div className='text-muted small'>Objetivo</div>
                                                            <div className='fw-bold text-primary'>{dailyCalorieGoal}</div>
                                                        </div>
                                                    </div>
                                                    <div className='col-4'>
                                                        <div className='text-center p-2 border rounded'>
                                                            <div className='text-muted small'>Consumidas</div>
                                                            <div className='fw-bold text-info'>{dailyCaloriesConsumed}</div>
                                                        </div>
                                                    </div>
                                                    <div className='col-4'>
                                                        <div className='text-center p-2 border rounded'>
                                                            <div className='text-muted small'>Restantes</div>
                                                            <div className={`fw-bold ${isOverGoal ? 'text-danger' : 'text-success'}`}>
                                                                {Math.abs(caloriesRemaining)}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div className='progress mb-2' style={{ height: '10px' }}>
                                                    <div
                                                        className={`progress-bar ${isOverGoal ? 'bg-danger' : 'bg-success'}`}
                                                        role='progressbar'
                                                        style={{ 
                                                            width: `${Math.min(100, (dailyCaloriesConsumed / dailyCalorieGoal) * 100)}%` 
                                                        }}
                                                    />
                                                </div>
                                                <div className='d-flex justify-content-between small text-muted'>
                                                    <span>0 kcal</span>
                                                    <span>{dailyCalorieGoal} kcal (objetivo)</span>
                                                </div>
                                            </div>
                                        )}
                                    </>
                                )}
                            </div>
                        </div>

                        {/* Add flags section with improved design - only show if not hiding advanced info */}
                        {!shouldHideAdvancedInfo && activeFlags.length > 0 && (
                            <div className="col-12">
                                <div className="row">
                                    <div className="col-12">
                                        <div className="d-flex align-items-center mb-4">
                                            <div className="flex-shrink-0">
                                                <Icon icon='EmojiEvents' size='2x' color='warning' />
                                            </div>
                                            <div className="flex-grow-1 ms-3">
                                                <h5 className="mb-0 text-primary fw-bold">Objetivos y Necesidades</h5>
                                                <small className="text-muted">Metas activas del usuario</small>
                                            </div>
                                        </div>
                                        <div className="d-flex flex-wrap gap-3">
                                            {activeFlags.map((flag, index) => (
                                                <div
                                                    key={index}
                                                    className={`badge bg-${flag.color} position-relative`}
                                                    style={{
                                                        fontSize: '0.9rem',
                                                        padding: '0.6rem 1.2rem',
                                                        borderRadius: '25px',
                                                        boxShadow: '0 4px 8px rgba(0,0,0,0.15)',
                                                        border: '2px solid rgba(255,255,255,0.2)',
                                                        backdropFilter: 'blur(10px)',
                                                        background: flag.color === 'primary' ? 'linear-gradient(135deg, #007bff, #0056b3)' :
                                                                   flag.color === 'warning' ? 'linear-gradient(135deg, #ffc107, #e0a800)' :
                                                                   flag.color === 'success' ? 'linear-gradient(135deg, #28a745, #1e7e34)' :
                                                                   flag.color === 'info' ? 'linear-gradient(135deg, #17a2b8, #117a8b)' :
                                                                   flag.color === 'secondary' ? 'linear-gradient(135deg, #6c757d, #545b62)' :
                                                                   flag.color === 'danger' ? 'linear-gradient(135deg, #dc3545, #c82333)' :
                                                                   'linear-gradient(135deg, #343a40, #23272b)',
                                                        color: 'white',
                                                        fontWeight: '600',
                                                        letterSpacing: '0.5px',
                                                        textShadow: '0 1px 2px rgba(0,0,0,0.3)',
                                                        transform: 'translateY(0)',
                                                        transition: 'all 0.2s ease',
                                                        cursor: 'default'
                                                    }}
                                                    onMouseEnter={(e) => {
                                                        e.currentTarget.style.transform = 'translateY(-2px)';
                                                        e.currentTarget.style.boxShadow = '0 6px 12px rgba(0,0,0,0.2)';
                                                    }}
                                                    onMouseLeave={(e) => {
                                                        e.currentTarget.style.transform = 'translateY(0)';
                                                        e.currentTarget.style.boxShadow = '0 4px 8px rgba(0,0,0,0.15)';
                                                    }}
                                                >
                                                    <span className="me-2">
                                                        {flag.color === 'primary' ? '💪' :
                                                         flag.color === 'warning' ? '⚖️' :
                                                         flag.color === 'success' ? '🎯' :
                                                         flag.color === 'info' ? '🏃‍♂️' :
                                                         flag.color === 'secondary' ? '🧠' :
                                                         flag.color === 'danger' ? '🩹' :
                                                         '🏥'}
                                                    </span>
                                                    {flag.label}
                                                    <div
                                                        className="position-absolute top-0 start-0 w-100 h-100"
                                                        style={{
                                                            background: 'linear-gradient(45deg, rgba(255,255,255,0.1), transparent)',
                                                            borderRadius: '25px',
                                                            pointerEvents: 'none'
                                                        }}
                                                    />
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </CardBody>
            </Card>
        </Fragment>
    )
}

export default PersonalInfoCard;