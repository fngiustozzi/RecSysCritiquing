/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package recsgenerator;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Properties;


public class DBConnection {
    private Statement _stmt = null;
    private Connection _con;
    public DBConnection(String database,String user,String password)
    {
          try
          {
           // _con = DriverManager.getConnection
           //         ("jdbc:mysql://localhost/" + database + "?" +
           //         "user=admin&password=admin");
               Properties props = new Properties();
               props.put("user", user);
               props.put("password",password);
               props.put("autoReconnect", "true");
               _con = DriverManager.getConnection("jdbc:mysql://localhost/"+database,props);
          }
          catch(SQLException e)
            {
                System.out.println("SQLException on DBConnection constructor" + e.getMessage());
            }
    }
    
    public synchronized String[] selectRecLanguage(int loID) {        
        String language="";
        String[] languageSeparados = new String[5];
        try {           
            ResultSet rs = null;
            String sql = "SELECT Language FROM lodata WHERE ID_LO = ?";
	    PreparedStatement selectConsult = _con.prepareStatement(sql);
            selectConsult.setInt(1,loID);
            rs = selectConsult.executeQuery();	        		
            if (rs != null && rs.next() ){
            //System.out.println("language");
                language = rs.getString("language");
                String delimitadores= ",";
                languageSeparados = language.split(delimitadores);
            }
        }
        catch(SQLException e)
         {
           System.out.println("SQLException on selectRecLanguage "+e.getMessage());
         }
        return languageSeparados;
    }
    
    public synchronized String selectRecTipoMat(int loID) {        
        String tipomat="";
        try {           
            ResultSet rs = null;
            String sql = "SELECT Material_Type FROM lodata WHERE ID_LO = ?";
	    PreparedStatement selectConsult = _con.prepareStatement(sql);
            selectConsult.setInt(1,loID);
            rs = selectConsult.executeQuery();	        		
            if (rs != null && rs.next() ){
            //System.out.println("language");
                tipomat = rs.getString("Material_Type");
            }
        }
        catch(SQLException e)
         {
           System.out.println("SQLException on selectRecTipoMat "+e.getMessage());
         }
        return tipomat;
    }
    
    public synchronized int addRec(int userID, int loID, int pred_value, String type) {
        int recID=0;
        try
        {
            String sql="INSERT INTO generated_recs " +
            "(ID_User,ID_LO,predicted_value,tipo) " + "VALUES (?,?,?,?)";
            PreparedStatement addNew = _con.prepareStatement(sql);
            addNew.setInt(1,userID);
            addNew.setInt(2,loID);
            addNew.setInt(3,pred_value);
            addNew.setString(4,type);
            addNew.executeUpdate();
            addNew.close();
            
            
        
            _stmt = _con.createStatement();
            ResultSet res = _stmt.executeQuery("SELECT ID_Rec FROM generated_recs WHERE ID_LO="+"'"+loID+"' AND ID_User="+"'"+userID+"'");
            while (res.next()) {
               recID = res.getInt(1);
            }
            _stmt.close();
            res.close();
        
        
            
        }
        catch(SQLException e)
         {
           System.out.println("SQLException on addRec "+e.getMessage());
         }
        return recID;
    }
}
