/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package recsgenerator;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import org.apache.mahout.cf.taste.common.TasteException;
import org.apache.mahout.cf.taste.eval.RecommenderBuilder;
import org.apache.mahout.cf.taste.impl.model.file.FileDataModel;
import org.apache.mahout.cf.taste.impl.neighborhood.NearestNUserNeighborhood;
import org.apache.mahout.cf.taste.impl.recommender.GenericUserBasedRecommender;
import org.apache.mahout.cf.taste.impl.similarity.EuclideanDistanceSimilarity;
import org.apache.mahout.cf.taste.impl.similarity.LogLikelihoodSimilarity;
import org.apache.mahout.cf.taste.impl.similarity.PearsonCorrelationSimilarity;
import org.apache.mahout.cf.taste.model.DataModel;
import org.apache.mahout.cf.taste.neighborhood.UserNeighborhood;
import org.apache.mahout.cf.taste.recommender.RecommendedItem;
import org.apache.mahout.cf.taste.recommender.Recommender;
import org.apache.mahout.cf.taste.similarity.UserSimilarity;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;
import org.json.*;

/**
 *
 * @author Henrique
 */
public class RecsGenerator {

    /**
     * @param args the command line arguments
     */
    static ArrayList<Integer> usersArr = new ArrayList<Integer>();
    
    static String[] usersArrLanguage = new String[1087000]; //Agregado
    static String[] usersArrTypeMat = new String[1087000]; //Agregado
    
    static String general = "k1.txt";
    //TROCAR NOMBES DOS CLUSTERS
    //static String[] clusters = {"k6-cluster5670.txt","k6-cluster5673.txt", "k6-cluster5677.txt", "k6-cluster5680.txt", "k6-cluster5684.txt", "k6-cluster5685.txt" };
        
    
    public static void main(String[] args) throws ParserConfigurationException, IOException, SAXException, TasteException {
        DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory.newInstance();
        DocumentBuilder docBuilder = docBuilderFactory.newDocumentBuilder();
        Document doc = docBuilder.parse(new File("config//config.xml"));
        doc.getDocumentElement().normalize();
        NodeList listConfig = doc.getElementsByTagName("usersList");
        Node usersListNode = listConfig.item(0);
        Element usersListElement = (Element)usersListNode;
        NodeList userIDList = usersListElement.getElementsByTagName("userID");
        //NodeList userLanguage = usersListElement.getElementsByTagName("Language");
        for(int j=0; j<userIDList.getLength(); j++){
            Element idElement = (Element)userIDList.item(j);
            NodeList auxList = idElement.getChildNodes();
            
            //ACA VER COMO SEPARAR IDIOMA Y TIPO DE MATERIAL          
            String uidAux = ((Node)auxList.item(0)).getNodeValue().trim();
            
            /* palabras separadas es un arreglo, 
             palabrasSepardas[0] tiene el iduser
             palabrasSepardas[1] tiene el idioma elegido
             palabrasSepardas[2] tiene el tipo de material elegido */
            String delimitadores= "-";
            String[] palabrasSeparadas = uidAux.split(delimitadores);
            //System.out.println(palabrasSeparadas[0]);
            
            String uid = palabrasSeparadas[0];
            usersArr.add( Integer.parseInt(uid));
            // ESTO LO USO PARA GENERAR LAS RECOMENDACIONES DESPUES DE LAS CRITICAS
            // EN usersArrLanguage GUARDO LOS LENGUAJES QUE PREFIEREN LOS USUARIOS
            // EN usersArrTypeMat GUARDO LOS TIPOS DE MATERIALES QUE PREFIEREN
            //System.out.println(usersArrLanguage[Integer.parseInt(uid)]);
            //System.out.println(Integer.parseInt(uid));
            if(uidAux.length()>7){
            usersArrLanguage[Integer.parseInt(uid)] = palabrasSeparadas[1];
            usersArrTypeMat[Integer.parseInt(uid)] = palabrasSeparadas[2];
            }
            //System.out.println(usersArrLanguage[Integer.parseInt(uid)]);
            //System.out.println(Integer.parseInt(uid));
            
        }
        
        DBConnection con = new DBConnection( "c9","root", "1234");
        FileWriter fw = new FileWriter(new File("recommendations.json"));
        /* Objeto JSON de resposta */
        JSONArray result = new JSONArray();
        /*
            Define que as mensagens de logs sejam do nível de erro 
            evitando assim, mensagens de notificação
        */
        System.setProperty("org.slf4j.simpleLogger.defaultLogLevel", "error");
        /*
            Em caso de logs de erros, estes devem ser escritos em um arquivo
        */
        System.setProperty("org.slf4j.simpleLogger.logFile", "MerlotRecommender.log");
        
        /* Construção de um recomendador */
        RecommenderBuilder builder = new RecommenderBuilder() {
            @Override
            public Recommender buildRecommender(DataModel dm) throws TasteException {
                UserSimilarity sim = new EuclideanDistanceSimilarity(dm);
                //UserSimilarity sim = new PearsonCorrelationSimilarity(dm);
                //UserSimilarity sim = new LogLikelihoodSimilarity(dm);
                UserNeighborhood neighborhood = new NearestNUserNeighborhood(10000, sim, dm);        
                return new GenericUserBasedRecommender(dm, neighborhood, sim);
            }
    	};
        
        for(int iduser : usersArr){            
            //Recomendação geral
            FileDataModel modelGeneral = new FileDataModel(new File(general));
            List<RecommendedItem> listGeneral = builder.buildRecommender(modelGeneral).recommend(iduser, 500); //CANTIDAD DE RECOMENDACIONES A GENERAR POR USUARIO
            //loop para acotar las recomendaciones generadas
            //ACA HACER EL TRUNCAMIENTO
            // accedo al idioma elegido por cada usuario asi: usersArrLanguage[iduser]
            // accedo al tipo de material elegido por cada usuario asi: usersArrTypeMat[iduser]
            List<RecommendedItem> listGeneralCritica; 
            listGeneralCritica = new ArrayList<RecommendedItem>();

            String[] languageRec;
            String tipoMaterial="";
            int indice = 0;
            for(RecommendedItem recom : listGeneral){
                if(listGeneral.size()>=4 && indice<4){ //QUIZAS NO FUNCIONE LA CONDICION listGeneral.size()>=4 PARA TODOS
                    languageRec = con.selectRecLanguage((int) recom.getItemID());
                    tipoMaterial = con.selectRecTipoMat((int) recom.getItemID());
                    boolean bandera = false;
                    for(int i=0; i<languageRec.length; i++){
                        if(usersArrLanguage[iduser].contains(languageRec[i])){
                            bandera = true;
                        }
                    }
                        
                    if ((usersArrTypeMat[iduser].contains(tipoMaterial)) 
                         && bandera/*(usersArrLanguage[iduser].contains(languageRec))*/){ 
                        //System.out.println(usersArrTypeMat[iduser]);
                        //System.out.println(tipoMaterial);        
                        listGeneralCritica.add(recom);
                        indice++;
                    }
                }
            }
           
            ArrayList<Integer> ids_rec_general = new ArrayList<Integer>(listGeneralCritica.size());
            for(int a1=0; a1<listGeneralCritica.size();a1++){
                ids_rec_general.add(a1, con.addRec(iduser, (int) (long)listGeneralCritica.get(a1).getItemID(), Math.round(listGeneralCritica.get(a1).getValue()), "general"));
            }
            
            JSONArray objectsG = new JSONArray();
            int a2=0;
            for(RecommendedItem rec : listGeneralCritica){
                JSONObject object = new JSONObject();
                object.put("value", Math.round(rec.getValue()));
                object.put("object", rec.getItemID()); 
                object.put("id_rec", ids_rec_general.get(a2));
                objectsG.put(object);
                a2++;
            }
            JSONObject recommendationsG = new JSONObject();
            recommendationsG.put("recommendations", objectsG);
            
            JSONObject obj_final = new JSONObject();
            obj_final.put("general", recommendationsG);
            obj_final.put("user", iduser);

            result.put(obj_final);

        }
        fw.append(result.toString(4));
        fw.close();
    }
    
    public static void printList(int iduser, List<RecommendedItem> l){
        System.out.println(iduser);
        for(RecommendedItem item : l){
            System.out.println(item);
        }
        
    }
    
}